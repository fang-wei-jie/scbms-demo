<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth:admin');

    }

    function view ()
    {

        $admin_sales_report = DB::table('features_preferences')->where('name', 'admin_sales_report')->first();

        return view('admin.dashboard', [
            'sales_report' => $admin_sales_report->value,
        ]);

    }

}
