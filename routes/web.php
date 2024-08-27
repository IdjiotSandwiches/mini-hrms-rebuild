<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\EditProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TakeAttendanceController;
use App\Http\Controllers\InputScheduleController;
use App\Http\Controllers\ReportController;

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

Route::middleware(['guest:web,admin'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/', 'index')->name('landing-page');
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'login')->name('attemptLogin');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register');
        Route::post('/register', 'register')->name('attemptRegister');
    });
});

Route::middleware(['auth:web,admin'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
