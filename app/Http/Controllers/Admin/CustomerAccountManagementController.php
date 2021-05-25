<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerAccountManagementController extends Controller
{
    function __construct()
    {

        $this->middleware('auth:admin');
    }

    function view()
    {
        return view('admin.customer_accounts', ['queried' => 0]);
    }

    function process(Request $request)
    {

        if (isset($_POST['query'])) {

            $name = $request->input('name');
            $phone = $request->input('phone');
            $email = $request->input('email');

            if ($email == null && $name != null && $phone != null) {

                $customer = DB::table('users')
                    ->where('name', $name)
                    ->where('phone', $phone)
                    ->get();
            } else if ($phone == null && $name != null && $email != null) {

                $customer = DB::table('users')
                    ->where('name', $name)
                    ->where('email', $email)
                    ->get();
            } else if ($name == null && $phone != null && $email != null) {

                $customer = DB::table('users')
                    ->where('phone', $phone)
                    ->where('email', $email)
                    ->get();
            } else if ($name != null && $phone != null && $email != null) {

                $customer = DB::table('users')
                    ->where('name', $name)
                    ->where('phone', $phone)
                    ->where('email', $email)
                    ->get();
            } else {
                return view('admin.customer_accounts', ['queried' => 2]);
            }

            return view('admin.customer_accounts', ['queried' => 1, 'customer' => $customer, 'count' => $customer->count()]);

        } else if (isset($_POST['reset_password'])){

            $reset = DB::table('users')
                ->where('id', $request->id)
                ->update(['password' => Hash::make($request->generated_password)]);

            if ($reset){
                return view('admin.customer_accounts', ['queried' => 0, 'reset' => 1]);
            } else {
                return view('admin.customer_accounts', ['queried' => 1, 'reset' => 2]);
            }

        }
    }
}
