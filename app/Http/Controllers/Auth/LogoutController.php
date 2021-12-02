<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    function logout (Request $request) {

        Auth::logout();
        Auth::guard('admin')->logout();
        Auth::guard('manager')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');

    }
}
