<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ViewBookingsController;
use App\Http\Controllers\MakeBookingsController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\RatesController;
use App\Http\Controllers\Admin\CustomerAccountManagementController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\AdminAccountController;

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

// GLOBAL PAGES

Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', [LoginController::class, 'view']) -> name('login');
Route::post('/login', [LoginController::class, 'auth']);

// CUSTOMER PAGES

// Registration
Route::get('/register', [RegisterController::class, 'view']) -> name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Logout
Route::post('/logout', [LogoutController::class, 'logout']) -> name('logout');

// My Account
Route::get('/myaccount', [AccountController::class, 'view']) -> name('myaccount');
Route::post('/myaccount', [AccountController::class, 'update']);

// My Bookings
Route::get('/mybookings', [ViewBookingsController::class, 'view_bookings']) -> name('mybookings');

// Book Courts
Route::get('/book-court', [MakeBookingsController::class, 'view_court']) -> name('book-court');
Route::post('/book-court', [MakeBookingsController::class, 'book_court']);

// Receipt/Invoice
Route::post('/receipt', [ReceiptController::class, 'view']) -> name('view-receipt');

// END OF CUSTOMER PAGES

// ADMIN PAGES
Route::prefix('admin')->group(function() {

    Route::get('/', function () {
        return redirect() -> route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'view']) -> name('admin.dashboard');

    // Check In
    Route::get('/checkin', [CheckInController::class, 'view']) -> name('admin.checkin');
    Route::post('/checkin', [CheckInController::class, 'check']);

    // Court Bookings
    Route::get('/bookings', [BookingsController::class, 'view']) -> name('admin.bookings');
    Route::post('/bookings', [BookingsController::class, 'delete']);

    // Rates Management
    Route::get('/rates', [RatesController::class, 'view']) -> name('admin.rates');
    Route::post('/rates', [RatesController::class, 'update']);
    Route::post('/rates-add', [RatesController::class, 'add']);

    // Accounts Management (Customer)
    Route::get('/accounts', [CustomerAccountManagementController::class, 'view']) -> name('admin.customer_accounts');
    Route::post('/accounts', [CustomerAccountManagementController::class, 'process']);

    // Sales Report
    // Route::get('/sales', [SalesController::class, 'view']) -> name('admin.sales');

    // My Account
    Route::get('/myaccount', [AdminAccountController::class, 'view']) -> name('admin.myadminaccount');
    Route::post('/myaccount', [AdminAccountController::class, 'update']);

});
