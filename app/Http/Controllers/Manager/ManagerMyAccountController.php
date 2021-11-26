<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Valuestore\Valuestore;

class ManagerMyAccountController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view () {

        return view ('manager.myaccount', ['settings' => Valuestore::make(storage_path('app/settings.json'))]);

    }

    function update (Request $request) {

        // get logged in account variables
        $manager = Auth::guard('manager') -> user();

        if (isset ($_POST["change-ID"])) {

            // validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:managers',
                'confirm-password' => 'required',

            ]);

            // save changes
            $manager->email = $request->email;
            $manager->save();

            // logout other logged in instances
            Auth::guard('manager')->logoutOtherDevices($request->input('confirm-password'));

            // redirect back to page with info prompt
            return back() -> with('info', 'Manager ID updated');

        } else if (isset ($_POST["change-name"]) ) {

            // validation
            $this -> validate($request, [

                'name' => 'required',

            ]);

            // save changes
            $manager->name = $request->input('name');
            $manager->save();

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
            if (!Hash::check($request->input('old-password'), $manager->password)) {

                return back() -> with('alert', 'Current password incorrect. Password not updated. ');

            } else {

                // check if new password matches confirm password field, if not redirect back with alert propr
                if ($request->input('new-password') != $request->input('confirm-password')) {

                    return back() -> with('alert', 'New password and confirm password does not match. Password not updated. ');

                }

            }

            // save changes
            $manager->password = Hash::make($request->input('new-password'));
            $manager->save();

            // logout other logged in instances
            Auth::guard('manager')->logoutOtherDevices($request->input('old-password'));

            // redirect back to page with info prompt
            return back() -> with('info', 'Password updated. ');

        }

    }
}
