<?php

namespace App\Services;

use App\Models\Ticket;
use Carbon\Carbon;

class TicketService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createTicket($request)
    {

        $priority = $request->priority ?? 'medium';

        $hours = match ($priority) {
            'low' => 72,
            'medium' => 48,
            'high' => 24,
            'critical' => 8,
            default => 48,
        };

        return Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'priority' => $priority,
            'status' => 'open',
            'sla_due_at' => Carbon::now()->addHours($hours),
        ]);

    }
}
