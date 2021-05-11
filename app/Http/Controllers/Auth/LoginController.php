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
            if (!Auth::attempt($request->only('email', 'password'))) {

                return back()->with('status', 'Invalid login details');
            }


            // store username and userID into session
            $request->session()->put('custName', Auth::user()->name);
            $request->session()->put('custID', Auth::user()->id);

            return redirect()->route('mybookings');

        } else if (isset($_POST['admin-login'])) {

            $this->validate($request, [

                // 'id' => 'required | exists:id',
                'email' => 'required',
                'password' => 'required'

            ]);

            // login verification
            if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {

                return back()->with('status', 'Invalid login details');
            }

            return redirect()->route('admin.dashboard');
        }
    }
}
