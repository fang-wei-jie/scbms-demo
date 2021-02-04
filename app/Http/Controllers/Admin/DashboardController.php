<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth:admin');

    }

    function view ()
    {

        $ratesEnabled = DB::table('rates')
            -> select('rateName', 'ratePrice')
            -> where('rateStatus', 1)
            -> get();

        $bookingRows = DB::table('bookings')
            -> join('rates', 'bookings.rateID', '=', 'rates.id')
            -> where('dateSlot', '=', date('Ymd'))
            -> where('timeSlot', '<=', date('H'))
            -> where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            -> orderBy('courtID', 'asc')
            -> get();

        return view ('admin.dashboard', ['ratesEnabled' => $ratesEnabled, 'bookings' => $bookingRows]);

    }

}
