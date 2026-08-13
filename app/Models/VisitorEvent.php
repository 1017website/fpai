<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event_type', 'session_id', 'ip_hash', 'path', 'section_slug',
        'referrer', 'user_agent', 'visited_at',
    ];

    protected function casts(): array
    {
        return ['visited_at' => 'datetime'];
    }
}
