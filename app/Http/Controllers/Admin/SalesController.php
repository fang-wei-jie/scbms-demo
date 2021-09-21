<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    function __construct()
    {

        $this->middleware('auth:admin');
    }

    function view()
    {

        if (DB::table('features_preferences')->where('name', 'admin_sales_report')->first()->value == 1) {

            return view('admin.sales');

        } else {

            return back();

        }


    }
}
