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

        $dayOfWeek = date("w");
        $startDateNumber = str_pad(date("d") - $dayOfWeek, 2, "0", STR_PAD_LEFT);
        $endDateNumber = str_pad(date("d") + (6 - $dayOfWeek), 2, "0", STR_PAD_LEFT);
        $weekQuery = '`created_at` BETWEEN "'.date('Y-m-').$startDateNumber.'%" AND "'.date('Y-m-').$endDateNumber.'%"';

        $weekSales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as weekSales')
            ->whereRaw($weekQuery)
            ->first();

        $todaySales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as todaySales')
            ->where('created_at', 'LIKE', date('Y-m-d').'%')
            ->first();

        $bookingRows = DB::table('bookings')
            -> join('rates', 'bookings.rateID', '=', 'rates.id')
            -> where('dateSlot', '=', date('Ymd'))
            -> where('timeSlot', '<=', date('H'))
            -> where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            -> orderBy('courtID', 'asc')
            -> get();

        $bookingCount = DB::table('bookings')
            -> join('rates', 'bookings.rateID', '=', 'rates.id')
            -> where('dateSlot', '=', date('Ymd'))
            -> where('timeSlot', '<=', date('H'))
            -> where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            -> orderBy('courtID', 'asc')
            -> count();

        $name = DB::table('operation_preferences')->where('attr', 'name')->first();
        $domain = DB::table('operation_preferences')->where('attr', 'domain')->first();
        $start_time = DB::table('operation_preferences')->where('attr', 'start_time')->first();
        $end_time = DB::table('operation_preferences')->where('attr', 'end_time')->first();

        return view ('manager.dashboard', [
            'ratesEnabled' => $ratesEnabled,
            'monthSales' => $monthSales->monthSales,
            'weekSales' => $weekSales->weekSales,
            'todaySales' => $todaySales->todaySales,
            'bookings' => $bookingRows,
            'bookingCount' => $bookingCount,
            "name" => $name->value,
            "domain" => $domain->value,
            "start_time" => $start_time->value,
            "end_time" => $end_time->value,
            "week_start_date" => $startDateNumber,
            "week_end_date" => $endDateNumber,
        ]);

    }
}
