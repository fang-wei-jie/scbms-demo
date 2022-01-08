<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Spatie\Valuestore\Valuestore;

class StaffSalesController extends Controller
{
    function __construct()
    {

        $this->middleware('auth:staff');
    }

    function view()
    {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        if ($settings->get('staff_sales_report') == 1) {

            return view('staff.sales');

        } else {

            return back();

        }


    }
}
