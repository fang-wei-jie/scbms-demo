<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Features;
use App\Models\Rates;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;

class ManagerDashboard extends Component
{
    public function render()
    {

        $ratesEnabled = (Features::where('name', 'rates')->first()->value == 1) ? true : false;
        $courts_count = Operation::where('attr', 'courts_count')->first()->value;

        $bookingRows = DB::table('bookings')
            ->join('rates', 'bookings.rateID', '=', 'rates.id')
            ->where('dateSlot', '=', date('Ymd'))
            ->where('timeSlot', '<=', date('H'))
            ->where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            ->orderBy('courtID', 'asc')
            ->get();

        if (Features::where('name', 'rates_weekend_weekday')->first()->value == 1) {
            // if weekend and weekday is in use, disable normal rate
            $rates = Rates::where('rateStatus', 1)->get()->where('id', '!=', 3);
        } else {
            // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
            $rates = Rates::where('rateStatus', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
        }

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

        return view('livewire.dashboard.manager-dashboard', [
            'rates_card_enabled' => $ratesEnabled,

            'courts_count' => $courts_count,
            'bookings' => $bookingRows,

            'rates' => $rates,
            
            'yearSales' => $yearSales->yearSales,
            'monthSales' => $monthSales->monthSales,
            // 'weekSales' => $weekSales->weekSales,
            'todaySales' => $todaySales->todaySales,
            // "week_start_date" => $startDateNumber,
            // "week_end_date" => $endDateNumber,
        ]);
    }
}
