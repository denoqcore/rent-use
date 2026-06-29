<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['owner_id', 'renter_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

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
        return auth()->id() === $this->owner_id ? $this->renter : $this->owner;
    }

    public static function findOrCreateBetween(int $userA, int $userB): self
    {
        $min = min($userA, $userB);
        $max = max($userA, $userB);

        return self::firstOrCreate(
            ['owner_id' => $min, 'renter_id' => $max]
        );
    }
}
