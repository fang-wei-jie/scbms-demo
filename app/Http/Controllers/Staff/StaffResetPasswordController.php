<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class StaffResetPasswordController extends Controller
{
    
    function __construct()
    {

        $this -> middleware('auth:staff');

    }

    function view () {

        // make sure request comes from manager panel managing staffs
        if (str_contains(URL::previous(), "/manager/staffs")) {
            return view ('staff.reset-password');
        } else {
            return back();
        }


    }

    function update (Request $request) {

        // make sure request comes from reset password page
        if (str_contains(URL::previous(), "staff/reset-password")) {

            $staff = Auth::guard('staff') -> user();

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
            $staff->password = Hash::make($request->password);
            $staff->save();

            // redirect back to page with info prompt
            return redirect() -> route('staff.dashboard');

            // logout other logged in instances
            Auth::guard('staff')->logoutOtherDevices($request->password);

        } else {
            return back();
        }

    }
}
