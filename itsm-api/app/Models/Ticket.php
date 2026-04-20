<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title', 'description', 'user_id', 'category_id', 'status', 'priority', 'sla_due_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function assignee()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)
            ->latest();
    }
}
