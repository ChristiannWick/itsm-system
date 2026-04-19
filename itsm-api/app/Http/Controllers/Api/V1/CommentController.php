<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Notification;

class CommentController extends Controller
{
    public function index($ticketId)
    {
        return Comment::with('user')
            ->where('ticket_id', $ticketId)
            ->latest()
            ->get();
    }

    public function store(Request $request, $ticketId)
    {
        $comment = Comment::create([
            'ticket_id' => $ticketId,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        $ticketOwnerId = $comment->ticket->user_id;

        if ($ticketOwnerId !== $request->user()->id) {
            Notification::create([
                'user_id' => $ticketOwnerId,
                'type' => 'comment',
                'data' => [
                    'message' => 'New comment on your ticket',
                    'ticket_id' => $ticketId,
                ],
            ]);
        }

        return $comment->load('user');
    }
}
