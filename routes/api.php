<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\UserController;

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
        Route::post('user-listing', [AdminController::class, 'userListing'])->name('admin.user-listing');
        Route::put('user-edit/{user:uuid}', [AdminController::class, 'userEdit'])->name('admin.user-update');
        Route::delete('user-delete/{user:uuid}', [AdminController::class, 'userDelete'])->name('admin.user-delete');
    });
});

/* User Endpoints */
Route::group(['prefix' => 'v1/user'], function () {
    Route::post('create', [UserController::class, 'register'])->name('user.create');
    Route::post('login', [UserController::class, 'login'])->name('user.login');

    Route::group(['middleware' => ['auth:api', 'user']], function () {
        Route::get('logout', [UserController::class, 'logout'])->name('user.logout');
        Route::get('/', [UserController::class, 'profile'])->name('user.profile');
        Route::get('/orders', [OrderController::class, 'index'])->name('user.orders');
        Route::delete('/', [UserController::class, 'delete'])->name('user.delete');
        Route::put('/edit', [UserController::class, 'update'])->name('user.update');
    });
});

/* Main Page Endpoints */
Route::group(['prefix' => 'v1/main'], function () {
    Route::get('promotions', [MainPageController::class, 'promotions'])->name('promotions');
    Route::get('blog', [MainPageController::class, 'posts'])->name('blog');
    Route::get('blog/{post:uuid}', [MainPageController::class, 'showPost'])->name('blog.show');
});

 