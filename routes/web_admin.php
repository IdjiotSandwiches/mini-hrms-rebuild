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

Route::prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::controller(ManagementController::class)->group(function () {
            Route::get('/management', 'index')->name('management');
            Route::get('/management/{keyword}', 'search')->name('search');
            Route::get('/management/edit/{username}', 'editPage')->name('edit-page');
            Route::put('/management/edit/{username}', 'edit')->name('edit');
        });
    });
