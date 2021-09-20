<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SalesCard extends Component
{
    public function render()
    {

        $yearSales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as yearSales')
            ->where('created_at', 'LIKE', date('Y').'%')
            ->first();

        $monthSales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as monthSales')
            ->where('created_at', 'LIKE', date('Y-m').'%')
            ->first();

        // $dayOfWeek = date("w");
        // $startDateNumber = str_pad(date("d") - $dayOfWeek, 2, "0", STR_PAD_LEFT);
        // $endDateNumber = str_pad(date("d") + (6 - $dayOfWeek), 2, "0", STR_PAD_LEFT);
        // $weekQuery = '`created_at` BETWEEN "'.date('Y-m-').$startDateNumber.'%" AND "'.date('Y-m-').$endDateNumber.'%"';

        // $weekSales = DB::table('bookings')
        //     ->selectRaw('SUM(timeLength*bookingPrice) as weekSales')
        //     ->whereRaw($weekQuery)
        //     ->first();

        $todaySales = DB::table('bookings')
            ->selectRaw('SUM(timeLength*bookingPrice) as todaySales')
            ->where('created_at', 'LIKE', date('Y-m-d').'%')
            ->first();

        return view('livewire.dashboard.sales-card', [
            'yearSales' => $yearSales->yearSales,
            'monthSales' => $monthSales->monthSales,
            // 'weekSales' => $weekSales->weekSales,
            'todaySales' => $todaySales->todaySales,
            // "week_start_date" => $startDateNumber,
            // "week_end_date" => $endDateNumber,
        ]);
    }
}
