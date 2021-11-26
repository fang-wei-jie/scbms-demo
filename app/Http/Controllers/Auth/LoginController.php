<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Features;
use Spatie\Valuestore\Valuestore;

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

        $settings = Valuestore::make(storage_path('app/settings.json'));

        if (isset($_POST['login'])) {

            $this->validate($request, [

                'email' => 'required | email',
                'password' => 'required'

            ]);

            $domain = $settings->get('domain');

            $managerDomain = '@'.$domain.'m';
            $adminDomain = '@'.$domain;

            if (str_ends_with($request->email, $managerDomain)) {

                // login verification for manager
                if (!Auth::guard('manager')->attempt(['email' => str_replace('@'.$domain.'m', "", $request->email), 'password' => $request->password])) {
                    return back()->with('status', 'Incorrect login credentials');
                }

                // logout other logged in instances
                Auth::guard('manager')->logoutOtherDevices($request->password);

                return redirect()->route('manager.dashboard');

            } else if (str_ends_with($request->email, $adminDomain) && $settings->get('admin_role') == 1) {

                // login verification for admin
                if (!Auth::guard('admin')->attempt(['email' => str_replace('@'.$domain, "", $request->email), 'password' => $request->password])) {
                    return back()->with('status', 'Incorrect login credentials');
                }

                // logout other logged in instances
                Auth::guard('admin')->logoutOtherDevices($request->password);

                return redirect()->route('admin.dashboard');

            } else {

                if (!Auth::attempt($request->only('email', 'password'), true)) {
                    return back()->with('status', 'Incorrect login credentials');
                }

                // logout other logged in instances
                Auth::logoutOtherDevices($request->password);

                return redirect()->route('mybookings');
            }
        }
    }
}
