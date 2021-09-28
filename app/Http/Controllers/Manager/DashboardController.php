<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Features;

class DashboardController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view ()
    {

        return view ('manager.dashboard');
        
    }
}
