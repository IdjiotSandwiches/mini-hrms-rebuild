<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'index')->name('landing-page');
    Route::get('/login', 'index')->name('login-page');
    Route::post('/login', 'login')->name('login');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'index')->name('register-page');
    Route::post('/register', 'register')->name('register');
});
