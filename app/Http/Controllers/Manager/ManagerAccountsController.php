<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManagerAccountsController extends Controller
{
    function __construct()
    {
        $this -> middleware('auth:manager');
    }

    function view()
    {

        $managers = DB::table('managers')->get();

        return view('manager.managers_management', ['managers' => $managers]);

    }

    function process(Request $request)
    {

        if(isset($_POST['add'])) {

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:managers',
                'name' => 'required | max:255',

            ]);

            // generate password
            $password = Str::random(25);

            // create manager
            Manager::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
            ]);

            // fetches new list of managers
            $managers = DB::table('managers')->get();

            return view('manager.managers_management', ['managers' => $managers, 'info' => "Manager ID for ".$request->name." was updated."]);

        } else if (isset($_POST['edit'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:managers',

            ]);

            // update admin detail
            $manager = Manager::find($request->id);
            $manager->email = $request->email;
            $manager->save();

            // fetches new list of managers
            $managers = DB::table('managers')->get();

            return view('manager.managers_management', ['managers' => $managers, 'info' => "Manager ID for ".$request->name." was updated."]);

        } else if (isset($_POST['delete'])){

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            Manager::where('id', $request->id)->delete();

        } else if (isset($_POST['reset'])) {

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            $randomPassword = Str::random(10);

            $admin = Manager::find($request->id);
            $admin->password = Hash::make($randomPassword);
            $admin->save();

            // fetches new list of managers
            $managers = DB::table('managers')->get();

            return view('manager.managers_management', ['managers' => $managers, 'info' => "Password for ".$admin->name." was resetted to ".$randomPassword]);

        }

        return redirect() -> route('manager.managers_management');

    }
}
