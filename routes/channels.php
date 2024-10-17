<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('base', function (\App\Models\User $user) {
    return true;
});
