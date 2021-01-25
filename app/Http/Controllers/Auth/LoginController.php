<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    function index () {

        return view ('auth.login');

    }

    function auth (Request $request) {

        $this -> validate($request, [

            'email' => 'required | email',
            'password' => 'required'

        ]);

        if (!Auth::attempt($request -> only('email', 'password'))) {

            return back() -> with('status', 'Invalid login details');

        }

        return redirect('/');

    }

}
