<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Valuestore\Valuestore;

class SalesController extends Controller
{
    function __construct()
    {

        $this->middleware('auth:admin');
    }

    function view()
    {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        if ($settings->get('admin_sales_report') == 1) {

            return view('admin.sales');

        } else {

            return back();

        }


    }
}
