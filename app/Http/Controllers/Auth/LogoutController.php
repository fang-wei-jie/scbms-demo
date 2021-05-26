<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    function logout () {

        Auth::logout();
        Auth::guard('admin')->logout;
        Auth::guard('manager')->logout;

        return redirect('/login');

    }
}
