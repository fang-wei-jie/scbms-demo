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

            return back()->with('info', "Manager ".$request->name." was added.");

        } else if (isset($_POST['edit'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:managers',

            ]);

            // update manager detail
            $manager = Manager::find($request->id);
            $manager->email = $request->email;
            $manager->save();

            return back()->with('info', "Manager ID for ".$manager->name." was updated.");

        } else if (isset($_POST['delete'])){

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            $manager = Manager::find($request->id);
            Manager::where('id', $request->id)->delete();

            return back()->with('info', "Manager ".$manager->name." was deleted.");

        } else if (isset($_POST['reset'])) {

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            $randomPassword = Str::random(10);

            $manager = Manager::find($request->id);
            $manager->password = Hash::make($randomPassword);
            $manager->save();

            return back()->with('info', "Password for ".$manager->name." was resetted to ".$randomPassword);

        }

        return redirect() -> route('manager.managers_management');

    }
}
