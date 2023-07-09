<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    // Register Error pindah kebawah 
    Route::get('/login', 'login')->name('login')->middleware('isLogin');
    Route::post('/login', 'doLogin')->name('do.login');
    Route::get('/logout', 'logout')->name('logout');
    // route untuk edit akun kasir setelah login
    // route untuk delete akun kasir setelah login
});

Route::middleware('auth')->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::resource('/category', CategoryController::class)->middleware('AdminOnly');
        Route::resource('/product', ProductController::class)->middleware('AdminOnly');
        Route::resource('/transaction', TransactionController::class)->middleware(('AdminOnly'));
        Route::resource('/detail-transaction', DetailTransactionController::class)->middleware('AdminOnly');

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->middleware('AdminOnly');
            Route::get('/cashier-account', 'account')->middleware('AdminOnly');
            Route::get('/cashier-account/edit/{id}', 'edit')->name('edit-cashier.view')->middleware('AdminOnly');
            
            Route::post('/cashier-account/edit/{id}', 'update')->middleware('AdminOnly');
            Route::post('/cashier-account/delete/{id}', 'destroy')->middleware('AdminOnly');
        });
    });
    
    Route::prefix('/cashier')->group(function () {
        Route::resource('/cart', CartController::class)->middleware('CheckRole');
        Route::resource('/order', OrderController::class)->middleware('CheckRole');
        Route::post('/checkout', [CartController::class, 'insertData'])->name('checkout');
        Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice');
        
        Route::get('/profile/{id}', [AuthController::class, 'edit'])->middleware('CheckRole');
        Route::post('/profile/{id}', [AuthController::class, 'update'])->middleware('CheckRole');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/admin/register', 'register')->name('register');
    Route::post('/register', 'doRegister')->name('do.register');
});

Route::redirect('/', '/login');