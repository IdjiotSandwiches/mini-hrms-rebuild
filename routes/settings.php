<?php

use Illuminate\Support\Facades\Route;

Route::get('.well-known/passkey-endpoints', function () {
    return response()->json([
        'enroll' => route('v2.settings.security.edit'),
        'manage' => route('v2.settings.security.edit'),
    ]);
})->name('well-known.passkeys');
