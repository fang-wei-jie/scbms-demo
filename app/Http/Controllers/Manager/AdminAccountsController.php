<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAccountsController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view()
    {

        $admins = DB::table('admins')->get();
        $domain = DB::table('operation_preferences') -> where('attr', 'domain') -> first();
        $adminDomain = '@'.$domain->value;

        return view('manager.admins_management', ['admins' => $admins, 'domain' => $adminDomain]);

    }

    function process(Request $request)
    {

        $domain = DB::table('operation_preferences') -> where('attr', 'domain') -> first();
        $adminDomain = '@'.$domain->value;

        if (isset($_POST['addAdmin'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:admins',
                'name' => 'required | max:255',

            ]);

            // generate a random password
            $password = Str::random(25);

            // create admin
            Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                ]);

            // fetches new list of admins
            $admins = DB::table('admins')->get();

            return view('manager.admins_management', ['admins' => $admins, 'domain' => $adminDomain, 'info' => "Successfully created ".$request->name." with password ".$password]);

        } else if (isset($_POST['editAdmin'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:admins',

            ]);

            // update admin detail
            $admin = Admin::find($request->id);
            $admin->email = $request->email;
            $admin->save();


            // DB::table('admins')
            //     ->where('id', $request->id)
            //     ->update(['email' => $request->email]);

            // fetches new list of admins
            $admins = DB::table('admins')->get();

            return view('manager.admins_management', ['admins' => $admins, 'domain' => $adminDomain, 'info' => "Admin ID for ".$request->name." was updated."]);

        } else if (isset($_POST['deleteAdmin'])){

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            Admin::where('id', $request->id)->delete();

        }

        return redirect() -> route('manager.admins_management');

    }
}
