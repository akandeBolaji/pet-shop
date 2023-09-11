<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrderStatusesController;

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
Route::group(['prefix' => 'v1/admin'], function (): void {
    Route::post('create', [AdminController::class, 'register'])->name('admin.create');
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');

    Route::group(['middleware' => ['auth:api', 'admin']], function (): void {
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::post('user-listing', [AdminController::class, 'userListing'])->name('admin.user-listing');
        Route::put('user-edit/{user:uuid}', [AdminController::class, 'userEdit'])->name('admin.user-update');
        Route::delete('user-delete/{user:uuid}', [AdminController::class, 'userDelete'])->name('admin.user-delete');
    });
});

/* User Endpoints */
Route::group(['prefix' => 'v1/user'], function (): void {
    Route::post('create', [UserController::class, 'register'])->name('user.create');
    Route::post('login', [UserController::class, 'login'])->name('user.login');

    Route::group(['middleware' => ['auth:api', 'user']], function (): void {
        Route::get('logout', [UserController::class, 'logout'])->name('user.logout');
        Route::get('/', [UserController::class, 'profile'])->name('user.profile');
        Route::get('/orders', [OrderController::class, 'index'])->name('user.orders');
        Route::delete('/', [UserController::class, 'delete'])->name('user.delete');
        Route::put('/edit', [UserController::class, 'update'])->name('user.update');
    });
});

/* Main Page Endpoints */
Route::group(['prefix' => 'v1/main'], function (): void {
    Route::get('promotions', [MainPageController::class, 'promotions'])->name('promotions');
    Route::get('blog', [MainPageController::class, 'posts'])->name('blog');
    Route::get('blog/{post:uuid}', [MainPageController::class, 'showPost'])->name('blog.show');
});

/* Brands Endpoints */
Route::get('v1/brands', [BrandsController::class, 'index'])->name('brands');
Route::group(['prefix' => 'v1/brand'], function (): void {
    Route::get('{brand:uuid}', [BrandsController::class, 'show'])->name('brand.show');

    Route::group(['middleware' => ['auth:api', 'admin']], function (): void {
        Route::post('create', [BrandsController::class, 'store'])->name('brand.create');
        Route::put('{brand:uuid}', [BrandsController::class, 'update'])->name('brand.update');
        Route::delete('{brand:uuid}', [BrandsController::class, 'destroy'])->name('brand.delete');
    });
});

/* Products Endpoints */
Route::get('v1/products', [ProductsController::class, 'index'])->name('products');
Route::group(['prefix' => 'v1/product'], function (): void {
    Route::get('/', [ProductsController::class, 'index'])->name('products');
    Route::get('{product:uuid}', [ProductsController::class, 'show'])->name('product.show');

    Route::group(['middleware' => ['auth:api', 'admin']], function (): void {
        Route::post('create', [ProductsController::class, 'store'])->name('product.create');
        Route::put('{product:uuid}', [ProductsController::class, 'update'])->name('product.update');
        Route::delete('{product:uuid}', [ProductsController::class, 'destroy'])->name('product.delete');
    });
});

/* Order statuses Endpoints */
Route::get('v1/order-statuses', [OrderStatusesController::class, 'index'])->name('order-statuses');
Route::group(['prefix' => 'v1/order-status'], function (): void {
    Route::get('{order_status:uuid}', [OrderStatusesController::class, 'show'])->name('order-status.show');

    Route::group(['middleware' => ['auth:api', 'admin']], function (): void {
        Route::post('create', [OrderStatusesController::class, 'store'])->name('order-status.create');
        Route::put('{order_status:uuid}', [OrderStatusesController::class, 'update'])->name('order-status.update');
        Route::delete('{order_status:uuid}', [OrderStatusesController::class, 'destroy'])->name('order-status.delete');
    });
});

/* Payments Endpoints */
Route::get('v1/payments', [PaymentsController::class, 'index'])->middleware(['auth:api', 'admin'])->name('payments');
Route::group(['prefix' => 'v1/payment', 'middleware' => ['auth:api']], function (): void {
    Route::post('create', [PaymentsController::class, 'store'])->middleware('user')->name('payment.create');

    Route::group(['middleware' => 'admin'], function (): void {
        Route::get('{payment:uuid}', [PaymentsController::class, 'show'])->name('payment.show');
        Route::put('{payment:uuid}', [PaymentsController::class, 'update'])->name('payment.update');
        Route::delete('{payment:uuid}', [PaymentsController::class, 'destroy'])->name('payment.delete');
    });
});

/* Files Endpoint */
Route::group(['prefix' => 'v1/file'], function (): void {
    Route::get('{file:uuid}', [FilesController::class, 'show'])->name('file.show');
    Route::post('/upload', [FilesController::class, 'store'])->middleware('auth:api')->name('file.upload');
});

/* Orders Endpoint */
Route::group(['prefix' => 'v1/orders', 'middleware' => ['auth:api', 'admin']], function (): void {
    Route::get('/', [OrdersController::class, 'index'])->name('orders');
    Route::get('dashboard', [OrdersController::class, 'index'])->name('orders.dashboard');
});

Route::group(['prefix' => 'v1/order', 'middleware' => ['auth:api']], function (): void {
    Route::get('{order:uuid}', [OrdersController::class, 'show'])->name('order.show');
    Route::post('create', [OrdersController::class, 'store'])->middleware('user')->name('order.create');

    Route::group(['middleware' => 'admin'], function (): void {
        Route::put('{order:uuid}', [OrdersController::class, 'update'])->name('order.update');
        Route::delete('{order:uuid}', [OrdersController::class, 'destroy'])->name('order.delete');
    });
});

/* Categories endpoints */
Route::get('v1/categories', [CategoriesController::class, 'index'])->name('categories');
Route::group(['prefix' => 'v1/category'], function (): void {
    Route::get('{category:uuid}', [CategoriesController::class, 'show'])->name('category.show');

    Route::group(['middleware' => ['auth:api', 'admin']], function (): void {
        Route::post('create', [CategoriesController::class, 'store'])->name('category.create');
        Route::put('{category:uuid}', [CategoriesController::class, 'update'])->name('category.update');
        Route::delete('{category:uuid}', [CategoriesController::class, 'destroy'])->name('category.delete');
    });
});
