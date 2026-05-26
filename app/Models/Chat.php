<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['listing_id', 'owner_id', 'renter_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function otherUser(): User
    {
        return auth()->id() === $this->owner_id ? $this->owner : $this->renter;
    }
}
