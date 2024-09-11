<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagementController;

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

Route::prefix('admin')->as('admin.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::prefix('management')->as('management.')->group(function () {
        Route::controller(ManagementController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{keyword}', 'search')->name('search');
            Route::get('/edit/{username}', 'editPage')->name('edit-page');
            Route::put('/edit/{username}', 'edit')->name('edit');
        });
    });
});
