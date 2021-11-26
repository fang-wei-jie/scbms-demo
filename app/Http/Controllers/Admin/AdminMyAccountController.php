<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminMyAccountController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:admin');

    }

    function view () {

        return view ('admin.myaccount');

    }

    function update (Request $request) {

        // get logged in account variables
        $admin = Auth::guard('admin') -> user();

        if (isset ($_POST["change-name"]) ) {

            // validation
            $this -> validate($request, [

                'name' => 'required',

            ]);

            // save changes
            $admin->name = $request->input('name');
            $admin->save();

            // redirect back to page with info prompt
            return back() -> with('info', 'Name updated');

        } else if (isset ($_POST["change-password"]) ) {

            // validate
            $this -> validate($request, [

                'old-password' => 'required',
                'new-password' => 'required',
                'confirm-password' => 'required',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (!Hash::check($request->input('old-password'), $admin->password)) {

                return back() -> with('alert', 'Current password incorrect. Password not updated. ');

            } else {

                // check if new password matches confirm password field, if not redirect back with alert propr
                if ($request->input('new-password') != $request->input('confirm-password')) {

                    return back() -> with('alert', 'New password and confirm password does not match. Password not updated. ');

                }

            }

            // save changes
            $admin->password = Hash::make($request->input('new-password'));
            $admin->save();
            
            // redirect back to page with info prompt
            return back() -> with('info', 'Password updated. ');

            // logout other logged in instances
            Auth::guard('admin')->logoutOtherDevices($request->input('old-password'));

        }

        return back();

    }
}
