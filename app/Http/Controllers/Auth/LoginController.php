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

                return redirect()->route('manager.dashboard');
            } else if (str_ends_with($request->email, $adminDomain) && $settings->get('admin_role') == 1) {

                // login verification for admin
                if (!Auth::guard('admin')->attempt(['email' => str_replace('@'.$domain, "", $request->email), 'password' => $request->password])) {
                    return back()->with('status', 'Incorrect login credentials');
                }

                return redirect()->route('admin.dashboard');
            } else {

                $remember = $request->has('remember') ? true : false;

                if (!Auth::attempt($request->only('email', 'password'), $remember)) {
                    return back()->with('status', 'Incorrect login credentials');
                }

                return redirect()->route('mybookings');
            }
        }
    }
}
