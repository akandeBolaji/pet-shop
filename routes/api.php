<?php

use App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Admin Endpoints */
Route::group(['prefix' => 'v1/admin'], function () {
    Route::post('create', [AdminController::class, 'register'])->name('admin.create');
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');

    Route::group(['middleware' => ['auth:api', 'admin']], function () {
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});

