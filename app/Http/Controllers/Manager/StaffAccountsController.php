<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Staff\StaffResetPasswordController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\Auth;

class StaffAccountsController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view()
    {

        // get list of staffs
        $staffs = DB::table('staffs')->get();

        return view('manager.staffs_management', [
            'staffs' => $staffs,
            'domain' => Valuestore::make(storage_path('app/settings.json'))->get('domain'),
        ]);

    }

    function process(Request $request)
    {

        if (isset($_POST['add'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:staffs',
                'name' => 'required | max:255',
                'password' => 'required | min:8 | max:255 | confirmed',

            ]);

            // create staff
            Staff::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return back()->with("info", "Successfully created ".$request->name.". ");

        } else if (isset($_POST['edit'])){

            // input validation
            $this -> validate($request, [

                'email' => 'required | max:25 | unique:staffs',

            ]);

            // update staff detail
            $staff = Staff::find($request->id);
            $staff->email = $request->email;
            $staff->save();

            return back()->with('info', "Staff ID for ".$staff->name." was updated.");

        } else if (isset($_POST['delete'])){

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            $staff = Staff::find($request->id);
            Staff::where('id', $request->id)->delete();

            return back()->with('info', "Staff ".$staff->name." was deleted.");

        } else if (isset($_POST['reset'])) {

            // input validation
            $this -> validate($request, [

                'id' => 'required',

            ]);

            // generate random string as password
            $randomPassword = Str::random(10);

            // update the password with the randomly generated password
            $staff = Staff::find($request->id);
            $staff->password = Hash::make($randomPassword);
            $staff->save();

            // logout from manager
            Auth::guard('manager')->logout();

            // login into the staff account
            // login verification for staff
            if (!Auth::guard('staff')->attempt(['email' => $staff->email, 'password' => $randomPassword])) {
                return back()->with('status', 'Incorrect login credentials');
            }
            
            // redirect to reset password page
            return redirect() -> route('staff.reset-password');

        }

        return back();

    }
}
