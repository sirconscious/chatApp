<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('test.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
