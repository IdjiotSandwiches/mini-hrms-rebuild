<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagementController;
use Illuminate\Support\Facades\Route;

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

    });

    Route::prefix('management')->as('management.')->group(function () {
        Route::controller(ManagementController::class)->group(function () {

        });
    });
});
