<?php

use App\Http\Controllers\V2\DashboardController;
use App\Http\Controllers\V2\InputScheduleController;
use App\Http\Controllers\V2\ManagementController;
use App\Http\Controllers\V2\ProfileController;
use App\Http\Controllers\V2\ReportController;
use App\Http\Controllers\V2\SecurityController;
use App\Http\Controllers\V2\TakeAttendanceController;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('v2')
    ->name('v2.')
    ->group(function () {
        Route::middleware(['can:attendance'])
            ->group(function () {
                Route::controller(TakeAttendanceController::class)
                    ->prefix('take-attendance')
                    ->name('take-attendance.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'store')->name('store');
                    });

                Route::controller(InputScheduleController::class)
                    ->prefix('input-schedule')
                    ->name('input-schedule.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'store')->name('store');
                    });

                Route::controller(ReportController::class)
                    ->prefix('report')
                    ->name('report.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                    });
            });

        Route::middleware(['can:admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::controller(DashboardController::class)
                    ->prefix('dashboard')
                    ->name('dashboard.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                    });

                Route::controller(ManagementController::class)
                    ->prefix('management')
                    ->name('management.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{id}', 'edit')->name('edit');
                        Route::match(['put', 'patch'], '/{id}', 'update')->name('update');
                        Route::delete('/{id}', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::redirect('/', '/profile');
                Route::inertia('/appearance', 'settings/Appearance')->name('appearance.edit');

                Route::controller(ProfileController::class)
                    ->group(function () {
                        Route::get('/profile', 'edit')->name('profile.edit');
                        Route::patch('/profile', 'update')->name('profile.update');
                        Route::delete('/profile', 'destroy')->name('profile.destroy');
                    });

                Route::controller(SecurityController::class)
                    ->group(function () {
                        Route::get('/security', 'edit')
                            ->middleware(RequirePassword::class)
                            ->name('security.edit');

                        Route::put('/password', 'update')
                            ->middleware('throttle:6,1')
                            ->name('user-password.update');
                    });
            });
    });

Route::get('.well-known/passkey-endpoints', function () {
    return response()->json([
        'enroll' => route('v2.settings.security.edit'),
        'manage' => route('v2.settings.security.edit'),
    ]);
})->name('well-known.passkeys');
