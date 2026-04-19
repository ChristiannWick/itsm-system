<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use Carbon\Carbon;


class TicketController extends Controller
{
    public function index(Request $request)
    {

        $query = Ticket::with(['user','category']);

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
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

    public function store(StoreTicketRequest $request)
    {
        // Simple SLA logic (we'll improve later)
        $priority = $request->priority ?? 'medium';
        
        $hours = match ($priority) {
            'low' => 72,
            'medium' => 48,
            'high' => 24,
            'critical' => 8,
            default => 48,
        };

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'priority' => $priority,
            'status' => 'open',
            'sla_due_at' => Carbon::now()->addHours($hours),
        ]);

        // return response()->json($ticket, 201);
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
