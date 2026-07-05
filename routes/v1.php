<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\ReportController;
use App\Http\Controllers\V1\ProfileController;
use App\Http\Controllers\V1\DashboardController;
use App\Http\Controllers\V1\ManagementController;
use App\Http\Controllers\V1\InputScheduleController;
use App\Http\Controllers\V1\TakeAttendanceController;

Route::middleware(['auth', 'verified'])
    ->prefix('v1')
    ->name('v1.')
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
                Route::get('/security', fn () => view('profile.change-password.index'))->name('security.edit');

                Route::controller(ProfileController::class)
                    ->group(function () {
                        Route::get('/profile', 'edit')->name('profile.edit');
                        Route::patch('/profile', 'update')->name('profile.update');
                        Route::delete('/profile', 'destroy')->name('profile.destroy');
                    });
            });
    });
