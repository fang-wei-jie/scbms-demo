<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class AdminResetPasswordController extends Controller
{
    
    function __construct()
    {

        $this -> middleware('auth:admin');

    }

    function view () {

        // make sure request comes from manager panel managing admins
        if (str_contains(URL::previous(), "/manager/admins")) {
            return view ('admin.reset-password');
        } else {
            return back();
        }


    }

    function update (Request $request) {

        // make sure request comes from reset password page
        if (str_contains(URL::previous(), "admin/reset-password")) {

            $admin = Auth::guard('admin') -> user();

            // validate
            $this -> validate($request, [
                
                'password' => 'required',
                'password_confirmation' => 'required',
                
            ]);

            // check if new password matches confirm password field, if not redirect back with alert propr
            if ($request->password != $request->password_confirmation) {
                return back() -> with('alert', 'New password and confirm password does not match. Password not updated. ');
                
            }
            
            // save changes
            $admin->password = Hash::make($request->password);
            $admin->save();

            // redirect back to page with info prompt
            return redirect() -> route('admin.dashboard');

            // logout other logged in instances
            Auth::guard('admin')->logoutOtherDevices($request->password);

        } else {
            return back();
        }

    }
}
