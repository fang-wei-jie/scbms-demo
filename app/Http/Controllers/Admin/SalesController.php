<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Features;

class SalesController extends Controller
{
    function __construct()
    {

        $this->middleware('auth:admin');
    }

    function view()
    {

        if (Features::where('name', 'admin_sales_report')->first()->value == 1) {

            return view('admin.sales');

        } else {

            return back();

        }


    }
}
