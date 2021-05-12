<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{

    function __construct()
    {

        $this -> middleware(['auth']);

    }

    function view () {

        return view ('customer.myaccount');

    }

    function update (Request $request) {

        $user = Auth::user();

        if (isset ($_POST["change-name"]) ) {

            // validation
            $this -> validate($request, [

                'name' => 'required',

            ]);

            // save changes
            $user->name = $request->input('name');
            $user->save();

            // set new name to the frame
            $request->session()->put('custName', $request->input('name'));

            // redirect back to page with info prompt
            return back() -> with('info', 'Name updated');

        } else if (isset ($_POST["change-phone"]) ) {

            // validate
            $this -> validate($request, [

                'phone' => 'required | min:10 | max:11',
                'phone-update-password' => 'required',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (!Hash::check($request->input('phone-update-password'), $user->password)) {

                return back() -> with('alert', 'Password incorrect. Phone number not updated');

            }

            // save changes
            $user->phone = $request->input('phone');
            $user->save();

            // redirect back to page with info prompt
            return back() -> with('info', 'Phone number updated. ');

        } else if (isset ($_POST["change-email"]) ) {

            // validate
            $this -> validate($request, [

                'email-update-password' => 'required',
                'email' => 'required | email | max:255',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (!Hash::check($request->input('email-update-password'), $user->password)) {

                return back() -> with('alert', 'Password incorrect. Email not updated');

            }

            // save changes
            $user->email = $request->input('email');
            $user->save();

            // redirect back to page with info prompt
            return back() -> with('info', 'Email updated. ');

        } else if (isset ($_POST["change-password"]) ) {

            // validate
            $this -> validate($request, [

                'old-password' => 'required',
                'new-password' => 'required',
                'confirm-password' => 'required',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (!Hash::check($request->input('old-password'), $user->password)) {

                return back() -> with('alert', 'Current password incorrect. Password not updated. ');

            } else {

                // check if new password matches confirm password field, if not redirect back with alert propr
                if ($request->input('new-password') != $request->input('confirm-password')) {

                    return back() -> with('alert', 'New password and confirm password does not match. Password not updated. ');

                }

            }

            // save changes
            $user->password = Hash::make($request->input('new-password'));
            $user->save();

            // redirect back to page with info prompt
            return back() -> with('info', 'Password updated. ');

        } else if (isset ($_POST["delete-account"]) ) {

            $this -> validate($request, [

                'delete-password' => 'required',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (Hash::check($request->input('delete-password'), $user->password)) {

                // empties unnecessary data field
                $user->name = 'DELETED';
                $user->phone = '';
                $user->email = Str::random(255);
                $user->password = '';
                $user->remember_token = null;
                $user->save();

            } else {

                return back() -> with('alert', 'Password incorrect. Account not deleted. ');

            }

            // redirect user out of the current page
            Auth::logout();
            return redirect() -> route('login');

        }

    }

}
