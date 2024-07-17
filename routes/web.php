<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TakeAttendanceController;

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

Route::middleware(['guest:web'])->group(function () {
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

Route::middleware(['auth:web'])->group(function () {
    Route::prefix('attendance')
        ->as('attendance.')
        ->group(function () {
            Route::controller(TakeAttendanceController::class)->group(function () {
                Route::get('/take-attendance', 'takeAttendancePage')->name('take-attendance-page');
                Route::post('/take-attendance', 'checkIn')->name('check-in');
                Route::put('/take-attendance', 'checkOut')->name('check-out');
            });

            // Route::get('/input-schedule', 'inputSchedulePage')->name('input-schedule-page');
            // Route::get('/report', 'reportPage')->name('report-page');
    });

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
