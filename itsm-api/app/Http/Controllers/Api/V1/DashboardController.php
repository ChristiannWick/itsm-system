<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return [
            'total' => Ticket::count(),

            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),

            'breached' => Ticket::whereNotNull('sla_due_at')
                ->where('sla_due_at', '<', Carbon::now())
                ->count(),
        ];
    }
}
