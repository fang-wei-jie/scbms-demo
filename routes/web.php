<?php

// Functions
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Auth\LogoutController;
use Spatie\Valuestore\Valuestore;
use App\Models\Rates;

// Customer Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerMyAccountController;
use App\Http\Controllers\MyBookingsController;
use App\Http\Controllers\MakeBookingsController;
use App\Http\Controllers\ReceiptController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminCheckInController;
use App\Http\Controllers\Admin\AdminCounterBookingController;
use App\Http\Controllers\Admin\AdminBookingsController;
use App\Http\Controllers\Admin\AdminRatesController;
use App\Http\Controllers\Admin\SalesController as AdminSalesController;
use App\Http\Controllers\Admin\AdminMyAccountController;
use App\Http\Controllers\CheckInTerminalController;
// Manager Controllers
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerCheckInController;
use App\Http\Controllers\Manager\ManagerCounterBookingController;
use App\Http\Controllers\Manager\ManagerBookingsController;
use App\Http\Controllers\Manager\AdminAccountsController;
use App\Http\Controllers\Manager\ManagerAccountsController;
use App\Http\Controllers\Manager\ManagerRatesController;
use App\Http\Controllers\Manager\SalesController as ManagerSalesController;
use App\Http\Controllers\Manager\ManagerMyAccountController;
use App\Http\Controllers\Manager\SettingsController;

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
    // get setting values
    $settings = Valuestore::make(storage_path('app/settings.json'));

    // check if rates use is enabled, if not return back
    if ($settings->get('rates') != 1) {
        return back();
    }

    if ($settings->get('rates_weekend_weekday') == 1) {
        // if weekend and weekday is in use, disable normal rate
        $default = Rates::get()->where('id', '<=', 2);
    } else {
        // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
        $default = Rates::where('id', 3)->get();
    }

    // get list of custom rates
    $custom = Rates::where('id', '>', 3)->get();
    
    return view('welcome', [
        'rates' => $default->merge($custom)->all(),
    ]);
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
Route::get('/mybookings', [MyBookingsController::class, 'view_bookings']) -> name('mybookings');
Route::post('/mybookings', [MyBookingsController::class, 'delete_bookings']);

// Book Courts
Route::get('/book-court', [MakeBookingsController::class, 'view']) -> name('book-court');
Route::post('/book-court', [MakeBookingsController::class, 'book_court']);

// Payment
Route::get('/payment', [MakeBookingsController::class, 'payment_preview']) -> name('preview-payment');
Route::post('/payment', [MakeBookingsController::class, 'payment_process']) -> name('process-payment');

// Receipt/Invoice
Route::get('/receipt', [ReceiptController::class, 'get_request']) -> name('view-receipt');
Route::post('/receipt', [ReceiptController::class, 'view']);

// About Us
Route::get('/about-us', function() {
    return view('about-us', ['settings' => Valuestore::make(storage_path('app/settings.json'))]);
}) -> name('about-us');

// Privacy Notice
Route::get('/privacy', function() {
    return view('privacy');
}) -> name('privacy');

// Terms of Use
Route::get('/terms', function() {
    return view('terms');
}) -> name('terms');

// Payment Methods
Route::get('/payment-methods', function() {
    return view('payment-methods');
}) -> name('payment-methods');

// Password Reset Request Form
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// Password Reset Request Handling
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// Password Reset Form
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Password Reset Request Handling
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// END OF CUSTOMER PAGES

// ADMIN PAGES
Route::prefix('admin')->group(function() {

    $settings = Valuestore::make(storage_path('app/settings.json'));

    if ($settings->get('admin_role') != 1) {
        Auth::guard('admin')->logout();
    }

    Route::get('/', function () {
        return redirect() -> route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'view']) -> name('admin.dashboard');

    // Make Booking
    Route::get('/book-court', [AdminCounterBookingController::class, 'view']) -> name('admin.book-court');
    Route::post('/book-court', [AdminCounterBookingController::class, 'book_court']);

    // Receipt Generator
    Route::get('/receipt', [AdminCounterBookingController::class, 'preview']) -> name('admin.receipt');
    Route::post('/receipt', [AdminCounterBookingController::class, 'receipt']);

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

    // Make Booking
    Route::get('/book-court', [ManagerCounterBookingController::class, 'view']) -> name('manager.book-court');
    Route::post('/book-court', [ManagerCounterBookingController::class, 'book_court']);

    // Receipt Generator
    Route::get('/receipt', [ManagerCounterBookingController::class, 'preview']) -> name('manager.receipt');
    Route::post('/receipt', [ManagerCounterBookingController::class, 'receipt']);
    
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

    // Settings
    Route::get('/settings', [SettingsController::class, 'view']) -> name('manager.settings');
    Route::post('/settings', [SettingsController::class, 'update']);

});

// CHECK-IN TERMINAL
Route::get('/check-in-terminal', [CheckInTerminalController::class, 'view']) -> name('check-in-terminal');
Route::post('/check-in-terminal', [CheckInTerminalController::class, 'check']);
