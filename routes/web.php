<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AccountController;

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

Route::get('/', function () {
    return view('welcome');
});

// Registration
Route::get('/register', [RegisterController::class, 'view']) -> name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login
Route::get('/login', [LoginController::class, 'view']) -> name('login');
Route::post('/login', [LoginController::class, 'auth']);

// Logout
Route::post('/logout', [LogoutController::class, 'logout']) -> name('logout');

// My Account
Route::get('/myaccount', [AccountController::class, 'view']) -> name('myaccount');
Route::post('/myaccount', [AccountController::class, 'update']);
