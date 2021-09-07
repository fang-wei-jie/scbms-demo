<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manager;
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
        $domain = DB::table('operation_preferences') -> where('attr', 'domain') -> first();
        $managerDomain = '@'.$domain->value.'m';

        return view('manager.managers_management', ['managers' => $managers, 'domain' => $managerDomain]);

    }

    function process(Request $request)
    {

        $domain = DB::table('operation_preferences') -> where('attr', 'domain') -> first();
        $managerDomain = '@'.$domain->value.'m';

        if(isset($_POST['addManager'])) {

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

            return view('manager.managers_management', ['managers' => $managers, 'domain' => $managerDomain, 'info' => "Manager ID for ".$request->name." was updated."]);

        } else if (isset($_POST['editManager'])){

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

            return view('manager.managers_management', ['managers' => $managers, 'domain' => $managerDomain, 'info' => "Manager ID for ".$request->name." was updated."]);

        } else if (isset($_POST['deleteManager'])){

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            Manager::where('id', $request->id)->delete();

        }

        return redirect() -> route('manager.managers_management');

    }
}
