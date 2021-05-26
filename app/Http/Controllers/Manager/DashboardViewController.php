<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardViewController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view ()
    {

        $ratesEnabled = DB::table('rates')
            -> select('rateName', 'ratePrice')
            -> where('rateStatus', 1)
            -> get();

        $monthSales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as monthSales')
            ->where('created_at', 'LIKE', date('Y-m').'%')
            ->first();

        $todaySales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as todaySales')
            ->where('created_at', 'LIKE', date('Y-m-d').'%')
            ->first();

        $todayBookingSales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as todayBookingSales')
            ->where('dateSlot', '=', date('Ymd'))
            ->first();

        $bookingRows = DB::table('bookings')
            -> join('rates', 'bookings.rateID', '=', 'rates.id')
            -> where('dateSlot', '=', date('Ymd'))
            -> where('timeSlot', '<=', date('H'))
            -> where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            -> orderBy('courtID', 'asc')
            -> get();

        return view ('manager.dashboard', ['ratesEnabled' => $ratesEnabled, 'monthSales' => $monthSales, 'todaySales' => $todaySales, 'todayBookingSales' => $todayBookingSales, 'bookings' => $bookingRows]);

    }
}
