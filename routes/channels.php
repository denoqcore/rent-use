<?php

use App\Models\Chat;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);

    return $chat && (
        $user->id === $chat->owner_id ||
        $user->id === $chat->renter_id
    );
});
