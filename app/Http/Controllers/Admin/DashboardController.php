<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth:admin');

    }

    function view ()
    {

        // dd(request());

        return view ('admin.dashboard');

    }

}
