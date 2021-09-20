<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view ()
    {

        $name = DB::table('operation_preferences')->where('attr', 'name')->first();
        $domain = DB::table('operation_preferences')->where('attr', 'domain')->first();
        $start_time = DB::table('operation_preferences')->where('attr', 'start_time')->first();
        $end_time = DB::table('operation_preferences')->where('attr', 'end_time')->first();

        return view ('manager.dashboard', [
            "name" => $name->value,
            "domain" => $domain->value,
            "start_time" => $start_time->value,
            "end_time" => $end_time->value,
        ]);

    }
}
