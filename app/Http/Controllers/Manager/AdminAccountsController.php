<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Admin\AdminResetPasswordController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\Auth;

class AdminAccountsController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view()
    {

        // get list of admins
        $admins = DB::table('admins')->get();

        return view('manager.admins_management', [
            'admins' => $admins,
            'domain' => Valuestore::make(storage_path('app/settings.json'))->get('domain'),
        ]);

    }

    function process(Request $request)
    {

        if (isset($_POST['add'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:admins',
                'name' => 'required | max:255',
                'password' => 'required | min:8 | max:255 | confirmed',

            ]);

            // create admin
            Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return back()->with("info", "Successfully created ".$request->name.". ");

        } else if (isset($_POST['edit'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:admins',

            ]);

            // update admin detail
            $admin = Admin::find($request->id);
            $admin->email = $request->email;
            $admin->save();

            return back()->with('info', "Admin ID for ".$admin->name." was updated.");

        } else if (isset($_POST['delete'])){

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            $admin = Admin::find($request->id);
            Admin::where('id', $request->id)->delete();

            return back()->with('info', "Admin ".$admin->name." was deleted.");

        } else if (isset($_POST['reset'])) {

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            // generate random string as password
            $randomPassword = Str::random(10);

            // update the password with the randomly generated password
            $admin = Admin::find($request->id);
            $admin->password = Hash::make($randomPassword);
            $admin->save();

            // logout from manager
            Auth::guard('manager')->logout();

            // login into the admin account
            // login verification for admin
            if (!Auth::guard('admin')->attempt(['email' => $admin->email, 'password' => $randomPassword])) {
                return back()->with('status', 'Incorrect login credentials');
            }
            
            // redirect to reset password page
            return redirect() -> route('admin.reset-password');

        }

        return back();

    }
}
