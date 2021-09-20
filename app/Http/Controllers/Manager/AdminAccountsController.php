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

        return view('manager.admins_management', ['admins' => $admins]);

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

            // fetches new list of admins
            $admins = DB::table('admins')->get();

            return view('manager.admins_management', ['admins' => $admins, 'info' => "Successfully created ".$request->name." with Admin ID ".$request->email.$adminDomain]);

        } else if (isset($_POST['edit'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:admins',

            ]);

            // update admin detail
            $admin = Admin::find($request->id);
            $admin->email = $request->email;
            $admin->save();

            // fetches new list of admins
            $admins = DB::table('admins')->get();

            return view('manager.admins_management', ['admins' => $admins, 'info' => "Admin ID for ".$admin->name." was updated."]);

        } else if (isset($_POST['delete'])){

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            Admin::where('id', $request->id)->delete();

        } else if (isset($_POST['reset'])) {

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            $randomPassword = Str::random(10);

            $admin = Admin::find($request->id);
            $admin->password = Hash::make($randomPassword);
            $admin->save();

            // fetches new list of admins
            $admins = DB::table('admins')->get();

            return view('manager.admins_management', ['admins' => $admins, 'info' => "Password for ".$admin->name." was resetted to ".$randomPassword]);

        }

        return redirect() -> route('manager.admins_management');

    }
}
