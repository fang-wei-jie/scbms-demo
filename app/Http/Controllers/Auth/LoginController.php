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

            return redirect()->route('mybookings');

        } else if (isset($_POST['admin-login'])) {

            $this->validate($request, [

                // 'id' => 'required | exists:id',
                'email' => 'required',
                'password' => 'required'

            ]);

            // identify whether it is admin or manager
            if (str_ends_with($request->email, '@xbcm')) {

                // login verification for manager
                if (!Auth::guard('manager')->attempt($request->only('email', 'password'))) {

                    return back()->with('status', 'Invalid login details');
                }

                return redirect()->route('manager.dashboard');

            } else if (str_ends_with($request->email, '@xbc')){

                // login verification for admin
                if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {

                    return back()->with('status', 'Invalid login details');
                }

                return redirect()->route('admin.dashboard');

            }

        }
    }
}
