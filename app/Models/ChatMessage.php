<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['chat_id', 'sender_id', 'body', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function isMine(): bool
    {
        return $this->sender_id === auth()->id();
    }
}
