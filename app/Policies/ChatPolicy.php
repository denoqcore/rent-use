<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function participate(User $user, Chat $chat): bool
    {
        return $user->id === $chat->owner_id || $user->id === $chat->renter_id;
    }
}
