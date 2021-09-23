<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Operation;
use App\Models\Features;

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

            $domain = Operation::where('attr', 'domain') -> first();

            $managerDomain = '@'.$domain->value.'m';
            $adminDomain = '@'.$domain->value;

            if (str_ends_with($request->email, $managerDomain)) {

                // login verification for manager
                if (!Auth::guard('manager')->attempt(['email' => str_replace('@'.$domain->value.'m', "", $request->email), 'password' => $request->password])) {
                    return back()->with('status', 'Invalid login details');
                }

                return redirect()->route('manager.dashboard');
            } else if (str_ends_with($request->email, $adminDomain) && Features::where('name', 'admin_role')->first()->value == 1) {

                // login verification for admin
                if (!Auth::guard('admin')->attempt(['email' => str_replace('@'.$domain->value, "", $request->email), 'password' => $request->password])) {
                    return back()->with('status', 'Invalid login details');
                }

                return redirect()->route('admin.dashboard');
            } else {

                if (!Auth::attempt($request->only('email', 'password'))) {
                    return back()->with('status', 'Invalid login details');
                }

                return redirect()->route('mybookings');
            }
        }
    }
}
