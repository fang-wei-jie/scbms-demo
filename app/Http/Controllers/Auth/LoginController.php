<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    function __construct()
    {

        $this->middleware(['guest']);
    }

    function view()
    {

        return view('auth.login');
    }

    function auth(Request $request)
    {

        if (isset($_POST['login'])) {

            $this->validate($request, [

                'email' => 'required | email',
                'password' => 'required'

            ]);

            // login verification
            if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {

                return back()->with('status', 'Invalid login details');
            }

            return redirect()->route('mybookings');

        } else if (isset($_POST['admin-login'])) {

            $this->validate($request, [

                // 'adminLoginID' => 'required | exists:adminLoginID',
                'adminLoginID' => 'required',
                'password' => 'required'

            ]);

            // login verification
            if (!Auth::guard('admin')->attempt($request->only('adminLoginID', 'password'), $request->remember)) {

                return back()->with(['status' => 'Invalid login details', 'switchtab' => 'pills-admin-login-tab']);
            }

            return redirect()->route('admin-dash');


        }
    }
}
