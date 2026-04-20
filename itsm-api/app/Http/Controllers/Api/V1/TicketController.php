<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\ActivityService;
use App\Services\TicketService;
use Carbon\Carbon;



class TicketController extends Controller
{
    public function index(Request $request)
    {

        $query = Ticket::with(['user','category']);

        if ($request->user()->role === 'user') {
            $query->where('user_id', $request->user()->id);
        }
        
        // 🔍 Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // 📌 Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ⚡ Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // 🗂 Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return TicketResource::collection(
            $query->latest()->paginate(10)
        );
    }

    public function store(StoreTicketRequest $request, TicketService $service, ActivityService $activityService)
    {
        
        // $ticket = $service->createTicket($request);

        // // return response()->json($ticket, 201);
        // return new TicketResource($ticket->load(['user', 'category']));

        $ticket = $service->createTicket($request);

        // $activityService->log(

        //     $ticket->id,

        //     $request->user()->id,

        //     'created',

        //     'Ticket created'

        // );

        return new TicketResource($ticket->load(['user', 'category']));
    }

    public function show(Ticket $ticket)
    {
        // return $ticket->load(['user', 'category']);
        return new TicketResource(
            $ticket->load(['user', 'category'])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {

        $user = $request->user();

        if (
            $user->id !== $ticket->user_id &&
            !$user->isAdmin() &&
            !$user->isAgent()
        ) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $ticket->update($request->validated());

        return new TicketResource(
            $ticket->load(['user', 'category'])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Ticket $ticket)
    {

        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Only admin can delete'], 403);
        }

        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted'
        ]);
    }

    public function assign(Request $request, Ticket $ticket, ActivityService $activityService)
    {

        $user = $request->user();

        // only admin or agent can assign
        if(
            $user->role !== 'admin' &&
            $user->role !== 'agent'
        ){
            return response()->json([
                'message'=>'Forbidden'
            ],403);
        }


        $ticket->assigned_to = $request->assigned_to;

        $ticket->status = 'in_progress';

        $ticket->save();

        $activityService->log(

            $ticket->id,

            $request->user()->id,

            'assigned',

            'Ticket assigned'

        );


        return $ticket->load('assignee');

    }

    public function activities(Ticket $ticket)
    {

        return $ticket->activities()
            ->with('user')
            ->get();

    }
}
