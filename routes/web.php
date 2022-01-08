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
use Illuminate\Support\Facades\Auth;

// Public Controllers
use App\Http\Controllers\CheckInTerminalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Customer Controllers
use App\Http\Controllers\CustomerMyAccountController;
use App\Http\Controllers\MyBookingsController;
use App\Http\Controllers\MakeBookingsController;
use App\Http\Controllers\ReceiptController;

// Staff Controllers
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffCheckInController;
use App\Http\Controllers\Staff\StaffCounterBookingController;
use App\Http\Controllers\Staff\StaffBookingsController;
use App\Http\Controllers\Staff\StaffRatesController;
use App\Http\Controllers\Staff\StaffSalesController;
use App\Http\Controllers\Staff\StaffMyAccountController;
use App\Http\Controllers\Staff\StaffResetPasswordController;

// Manager Controllers
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerCheckInController;
use App\Http\Controllers\Manager\ManagerCounterBookingController;
use App\Http\Controllers\Manager\ManagerBookingsController;
use App\Http\Controllers\Manager\StaffAccountsController;
use App\Http\Controllers\Manager\ManagerAccountsController;
use App\Http\Controllers\Manager\ManagerRatesController;
use App\Http\Controllers\Manager\ManagerSalesController;
use App\Http\Controllers\Manager\ManagerMyAccountController;
use App\Http\Controllers\Manager\ManagerResetPasswordController;
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

    // redirect if user already logged in
    if (Auth::guard('manager')->check()) {
        return redirect() -> route('manager.dashboard');
    } else if (Auth::guard('staff')->check()) {
        return redirect() -> route('staff.dashboard');
    } else if (Auth::check()) {
        return redirect() -> route('mybookings');
    }
    
    return view('welcome', [
        'rates' => Rates::where('status', 1)->get(),
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
Route::post('/book-court', [MakeBookingsController::class, 'check_booking']);
Route::post('/confirm-booking', [MakeBookingsController::class, 'confirm_booking']) -> name('confirm-booking');

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
Route::prefix('staff')->group(function() {

    $settings = Valuestore::make(storage_path('app/settings.json'));

    if ($settings->get('staff_role') != 1) {
        Auth::guard('staff')->logout();
    }

    Route::get('/', function () {
        return redirect() -> route('staff.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [StaffDashboardController::class, 'view']) -> name('staff.dashboard');

    // Make Booking
    Route::get('/book-court', [StaffCounterBookingController::class, 'view']) -> name('staff.book-court');
    Route::post('/book-court', [StaffCounterBookingController::class, 'check_booking']);
    Route::post('/confirm-booking', [StaffCounterBookingController::class, 'confirm_booking']) -> name('staff.confirm-booking');

    // Receipt Generator
    Route::get('/receipt', [StaffCounterBookingController::class, 'preview']) -> name('staff.receipt');
    Route::post('/receipt', [StaffCounterBookingController::class, 'receipt']);

    // Check In
    Route::get('/checkin', [StaffCheckInController::class, 'view']) -> name('staff.checkin');
    Route::post('/checkin', [StaffCheckInController::class, 'check']);

    // Court Bookings
    Route::get('/bookings', [StaffBookingsController::class, 'view']) -> name('staff.bookings');
    Route::post('/bookings', [StaffBookingsController::class, 'cancel']);

    // Rates Management
    Route::get('/rates', [StaffRatesController::class, 'view']) -> name('staff.rates');
    Route::post('/rates', [StaffRatesController::class, 'process']);
    Route::get('/rates-default-switch', function() {
        return back();
    });
    Route::post('/rates-default-switch', [StaffRatesController::class, 'update']) -> name('staff.default_switch');

    // Sales Report
    Route::get('/sales', [StaffSalesController::class, 'view']) -> name('staff.sales');

    // My Account
    Route::get('/myaccount', [StaffMyAccountController::class, 'view']) -> name('staff.myaccount');
    Route::post('/myaccount', [StaffMyAccountController::class, 'update']);

    // Password Reset
    Route::get('/reset-password', [StaffResetPasswordController::class, 'view']) -> name('staff.reset-password');
    Route::post('/reset-password', [StaffResetPasswordController::class, 'update']);

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
    Route::post('/book-court', [ManagerCounterBookingController::class, 'check_booking']);
    Route::post('/confirm-booking', [ManagerCounterBookingController::class, 'confirm_booking']) -> name('manager.confirm-booking');


    // Receipt Generator
    Route::get('/receipt', [ManagerCounterBookingController::class, 'preview']) -> name('manager.receipt');
    Route::post('/receipt', [ManagerCounterBookingController::class, 'receipt']);
    
    // Check In
    Route::get('/checkin', [ManagerCheckInController::class, 'view']) -> name('manager.checkin');
    Route::post('/checkin', [ManagerCheckInController::class, 'check']);

    // Court Bookings
    Route::get('/bookings', [ManagerBookingsController::class, 'view']) -> name('manager.bookings');
    Route::post('/bookings', [ManagerBookingsController::class, 'cancel']);

    // Staff Accounts Management
    Route::get('/staffs', [StaffAccountsController::class, 'view']) -> name('manager.staffs_management');
    Route::post('/staffs', [StaffAccountsController::class, 'process']);

    // Manager Accounts Management
    Route::get('/managers', [ManagerAccountsController::class, 'view']) -> name('manager.managers_management');
    Route::post('/managers', [ManagerAccountsController::class, 'process']);

    // Rates Management
    Route::get('/rates', [ManagerRatesController::class, 'view']) -> name('manager.rates');
    Route::post('/rates', [ManagerRatesController::class, 'process']);
    Route::get('/rates-default-switch', function() {
        return back();
    });
    Route::post('/rates-default-switch', [ManagerRatesController::class, 'update']) -> name('manager.default_switch');

    // Sales Report
    Route::get('/sales', [ManagerSalesController::class, 'view']) -> name('manager.sales');

    // My Account
    Route::get('/myaccount', [ManagerMyAccountController::class, 'view']) -> name('manager.myaccount');
    Route::post('/myaccount', [ManagerMyAccountController::class, 'update']);

    // Settings
    Route::get('/settings', [SettingsController::class, 'view']) -> name('manager.settings');
    Route::post('/settings', [SettingsController::class, 'update']);

    // Password Reset
    Route::get('/reset-password', [ManagerResetPasswordController::class, 'view']) -> name('manager.reset-password');
    Route::post('/reset-password', [ManagerResetPasswordController::class, 'update']);

});

// CHECK-IN TERMINAL
Route::get('/check-in-terminal', [CheckInTerminalController::class, 'view']) -> name('check-in-terminal');
Route::post('/check-in-terminal', [CheckInTerminalController::class, 'check']);
