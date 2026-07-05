<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->middleware(['guest']);
Route::redirect('/', '/v2/take-attendance')->middleware(['auth', 'verified']);

require __DIR__.'/v1.php';
require __DIR__.'/v2.php';
