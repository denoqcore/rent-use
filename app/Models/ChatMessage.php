<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['chat_id', 'sender_id', 'listing_id', 'body', 'read_at', 'edited_at'];

    protected $casts = [
        'read_at'   => 'datetime',
        'edited_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
