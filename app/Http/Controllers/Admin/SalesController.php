<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
    }

    function view()
    {

        // SELECT SUM(timeLength*bookingPrice) as monthSales FROM bookings WHERE created_at LIKE '2020-12%'
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

        return view('admin.sales', ['monthSales' => $monthSales, 'todaySales' => $todaySales, 'todayBookingSales' => $todayBookingSales]);
    }
}
