<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class ManagerResetPasswordController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view () {

        // make sure request comes from manager panel managing staffs
        if (str_contains(URL::previous(), "/manager/managers")) {
            return view ('manager.reset-password');
        } else {
            return back();
        }

    }

    function update (Request $request) {

        // make sure request comes from reset password page
        if (str_contains(URL::previous(), "manager/reset-password")) {

            $manager = Auth::guard('manager') -> user();

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
            $manager->password = Hash::make($request->password);
            $manager->save();

            // redirect back to page with info prompt
            return redirect() -> route('manager.dashboard');

            // logout other logged in instances
            Auth::guard('manager')->logoutOtherDevices($request->password);

        } else {
            return back();
        }

    }
}
