<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_name',
    ];

    // Relationship: a session has many chat logs
    public function chatLogs()
    {
        return $this->hasMany(ChatLog::class, 'chat_session_id');
    }

    // Relationship: a session belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
