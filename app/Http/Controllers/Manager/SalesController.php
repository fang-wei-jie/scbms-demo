<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:manager');
    }

    function view()
    {

        return view('manager.sales');
        
    }
}
