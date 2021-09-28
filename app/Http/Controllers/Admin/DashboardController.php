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

        return view('admin.dashboard');

    }

}
