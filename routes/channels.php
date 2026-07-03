<?php

use Illuminate\Support\Arr;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversations.{id}', function ($user, $id) {
    return $user->inConversation($id);

});

Broadcast::channel('chat.{roomId}', function ($user, $id) {
    return Arr::only($user->toArray(), [
        'id',
        'name',
    ]);
});

Broadcast::channel('notification', function ($user) {
    return Auth::check();
});
