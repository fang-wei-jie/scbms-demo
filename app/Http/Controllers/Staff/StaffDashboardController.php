<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class StaffDashboardController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth:staff');

    }

    function view ()
    {

        return view('staff.dashboard');

    }

}
