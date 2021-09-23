<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Features;

class DashboardController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth:admin');

    }

    function view ()
    {

        $admin_sales_report = Features::where('name', 'admin_sales_report')->first();

        return view('admin.dashboard', [
            'sales_report' => $admin_sales_report->value,
        ]);

    }

}
