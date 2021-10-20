<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth:admin');

    }

    function view ()
    {

        return view('admin.dashboard');

    }

}
