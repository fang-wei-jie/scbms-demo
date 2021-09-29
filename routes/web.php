<?php

// Functions
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\LogoutController;

// Customer Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerMyAccountController;
use App\Http\Controllers\ViewBookingsController;
use App\Http\Controllers\MakeBookingsController;
use App\Http\Controllers\ReceiptController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminCheckInController;
use App\Http\Controllers\Admin\AdminBookingsController;
use App\Http\Controllers\Admin\AdminRatesController;
use App\Http\Controllers\Admin\SalesController as AdminSalesController;
use App\Http\Controllers\Admin\AdminMyAccountController;

// Manager Controllers
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerCheckInController;
use App\Http\Controllers\Manager\ManagerBookingsController;
use App\Http\Controllers\Manager\AdminAccountsController;
use App\Http\Controllers\Manager\ManagerAccountsController;
use App\Http\Controllers\Manager\ManagerRatesController;
use App\Http\Controllers\Manager\SalesController as ManagerSalesController;
use App\Http\Controllers\Manager\ManagerMyAccountController;
use App\Http\Controllers\Manager\PreferencesController;

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
Route::get('/logout', function() {
    return back();
});
Route::post('/logout', [LogoutController::class, 'logout']) -> name('logout');

// My Account
Route::get('/myaccount', [CustomerMyAccountController::class, 'view']) -> name('myaccount');
Route::post('/myaccount', [CustomerMyAccountController::class, 'update']);

// My Bookings
Route::get('/mybookings', [ViewBookingsController::class, 'view_bookings']) -> name('mybookings');

// Book Courts
Route::get('/book-court', [MakeBookingsController::class, 'view']) -> name('book-court');
Route::post('/book-court', [MakeBookingsController::class, 'book_court']);

// Receipt/Invoice
Route::get('/receipt', [ReceiptController::class, 'get_request']) -> name('view-receipt');
Route::post('/receipt', [ReceiptController::class, 'view']);

// END OF CUSTOMER PAGES

// ADMIN PAGES
Route::prefix('admin')->group(function() {

    if (DB::table('features_preferences')->where('name', 'admin_role')->first()->value != 1) {
        Auth::guard('admin')->logout();
    }

    Route::get('/', function () {
        return redirect() -> route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'view']) -> name('admin.dashboard');

    // Check In
    Route::get('/checkin', [AdminCheckInController::class, 'view']) -> name('admin.checkin');
    Route::post('/checkin', [AdminCheckInController::class, 'check']);

    // Court Bookings
    Route::get('/bookings', [AdminBookingsController::class, 'view']) -> name('admin.bookings');
    Route::post('/bookings', [AdminBookingsController::class, 'delete']);

    // Rates Management
    Route::get('/rates', [AdminRatesController::class, 'view']) -> name('admin.rates');
    Route::post('/rates', [AdminRatesController::class, 'process']);

    // Sales Report
    Route::get('/sales', [AdminSalesController::class, 'view']) -> name('admin.sales');

    // My Account
    Route::get('/myaccount', [AdminMyAccountController::class, 'view']) -> name('admin.myaccount');
    Route::post('/myaccount', [AdminMyAccountController::class, 'update']);

});

// MANAGER PAGES
Route::prefix('manager')->group(function() {

    Route::get('/', function () {
        return redirect() -> route('manager.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [ManagerDashboardController::class, 'view']) -> name('manager.dashboard');

    // Check In
    Route::get('/checkin', [ManagerCheckInController::class, 'view']) -> name('manager.checkin');
    Route::post('/checkin', [ManagerCheckInController::class, 'check']);

    // Court Bookings
    Route::get('/bookings', [ManagerBookingsController::class, 'view']) -> name('manager.bookings');
    Route::post('/bookings', [ManagerBookingsController::class, 'delete']);

    // Admin Accounts Management
    Route::get('/admins', [AdminAccountsController::class, 'view']) -> name('manager.admins_management');
    Route::post('/admins', [AdminAccountsController::class, 'process']);

    // Manager Accounts Management
    Route::get('/managers', [ManagerAccountsController::class, 'view']) -> name('manager.managers_management');
    Route::post('/managers', [ManagerAccountsController::class, 'process']);

    // Rates Management
    Route::get('/rates', [ManagerRatesController::class, 'view']) -> name('manager.rates');
    Route::post('/rates', [ManagerRatesController::class, 'process']);

    // Sales Report
    Route::get('/sales', [ManagerSalesController::class, 'view']) -> name('manager.sales');

    // My Account
    Route::get('/myaccount', [ManagerMyAccountController::class, 'view']) -> name('manager.myaccount');
    Route::post('/myaccount', [ManagerMyAccountController::class, 'update']);

    // Prefernces
    Route::get('/preferences', [PreferencesController::class, 'view']) -> name('manager.preferences');
    Route::post('/preferences', [PreferencesController::class, 'update']);

});
