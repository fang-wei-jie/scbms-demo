<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Valuestore\Valuestore;

class ManagerAccountsController extends Controller
{
    function __construct()
    {
        $this -> middleware('auth:manager');
    }

    function view()
    {

        // get list of managers
        $managers = DB::table('managers')->get();

        return view('manager.managers_management', [
            'managers' => $managers,            
            'domain' => Valuestore::make(storage_path('app/settings.json'))->get('domain')
        ]);

    }

    function process(Request $request)
    {

        // if the request process on the ucrrent user, prevent it and return back
        if ($request->id == Auth::user()->id) {
            return back();
        }

        if(isset($_POST['add'])) {

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:managers',
                'name' => 'required | max:255',
                'password' => 'required | min:8 | max:255 | confirmed',

            ]);

            // create manager
            Manager::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
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

            // generate random string as password
            $randomPassword = Str::random(10);

            // update the password with the randomly generated password
            $manager = Manager::find($request->id);
            $manager->password = Hash::make($randomPassword);
            $manager->save();

            // logout from manager
            Auth::guard('manager')->logout();

            // login into the manager account
            // login verification for manager
            if (!Auth::guard('manager')->attempt(['email' => $manager->email, 'password' => $randomPassword])) {
                return back()->with('status', 'Incorrect login credentials');
            }
            
            // redirect to reset password page
            return redirect()->route('manager.reset-password');
            
        }

        return redirect() -> route('manager.managers_management');

    }
}
