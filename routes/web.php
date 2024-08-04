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
                Route::get('/take-attendance', 'index')->name('take-attendance-page');
                Route::post('/take-attendance', 'checkIn')->name('check-in');
                Route::put('/take-attendance', 'checkOut')->name('check-out');
            });

            Route::controller(InputScheduleController::class)->group(function () {
                Route::get('/input-schedule', 'index')->name('input-schedule-page');
                Route::get('/update-schedule', 'update')->name('update-schedule-page');
                Route::post('/input-schedule', 'inputSchedule')->name('input-schedule');
            });

            Route::controller(ReportController::class)->group(function () {
                Route::get('/report', 'index')->name('report-page');
                Route::get('/range-report', 'rangeReport')->name('get-range-report');
            });
    });

    Route::prefix('profile')
        ->as('profile.')
        ->group(function () {
            Route::controller(EditProfileController::class)->group(function () {
                Route::get('/edit-profile', 'index')->name('edit-profile-page');
                Route::put('/edit-profile', 'editProfile')->name('edit-profile');
            });

            Route::controller(ChangePasswordController::class)->group(function () {
                Route::get('/change-password', 'index')->name('change-password-page');
                Route::put('/change-password', 'changePassword')->name('change-password');
            });
        });

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
