<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    public function index(Request $request)
    {

        $query = Ticket::with(['user','category']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        return TicketResource::collection(
            $query->latest()->paginate(10)
        );
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
        ]);

        return response()->json($ticket, 201);
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
        $ticket->update($request->validated());

        return new TicketResource(
            $ticket->load(['user', 'category'])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted'
        ]);
    }
}
