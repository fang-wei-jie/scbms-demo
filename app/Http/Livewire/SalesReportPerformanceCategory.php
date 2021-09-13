<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SalesReportPerformanceCategory extends Component
{

    public $type = "";

    public function render()
    {

        $condition = 'LIKE "' . date("Y-m-d%");

        $dayOfWeek = date("w");
        $startDateNumber = str_pad(date("d") - $dayOfWeek, 2, "0", STR_PAD_LEFT);
        $endDateNumber = str_pad(date("d") + (6 - $dayOfWeek), 2, "0", STR_PAD_LEFT);

        switch (date("m")) {
            case "1": $monthString = "January"; break;
            case "2": $monthString = "February"; break;
            case "3": $monthString = "March"; break;
            case "4": $monthString = "April"; break;
            case "5": $monthString = "May"; break;
            case "6": $monthString = "June"; break;
            case "7": $monthString = "July"; break;
            case "8": $monthString = "August"; break;
            case "9": $monthString = "September"; break;
            case "10": $monthString = "October"; break;
            case "11": $monthString = "November"; break;
            case "12": $monthString = "December"; break;
        }

        switch ($this->type) {
            case 'd':
                $condition = 'LIKE "' . date("Y-m-d%");
                $period = date("d") . " " . $monthString . " " . date("Y");
                break;
            case 'w':
                $condition = 'BETWEEN "' . date('Y-m-') . $startDateNumber . '%" AND "' . date('Y-m-') . $endDateNumber . '%';
                $period = $startDateNumber . date('/m/Y') . " till " . $endDateNumber . date('/m/Y');
                break;
            case 'm':
                $condition = 'LIKE "' . date("Y-m%");
                $period = $monthString . " " . date("Y");
                break;
            case 'y':
                $condition = 'LIKE "' . date("Y%");
                $period = "Year " . date("Y");
                break;

            default:
                $condition = 'LIKE "' . date("Y-m-d%");
                $period = date("d") . " " . $monthString . " " . date("Y");
                break;
        }

        $ratesPerf = DB::table('bookings')
            ->join('rates', 'rates.id', '=', 'bookings.rateID')
            ->selectRaw('rates.rateName as rate, SUM(timeLength*bookingPrice) as total')
            ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
            ->groupBy('rates.rateName')
            ->get();

        $timeslotPerf = DB::table('bookings')
            ->selectRaw('timeSlot as slot, SUM(timeLength*bookingPrice) as total')
            ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
            ->groupBy('bookings.timeSlot')
            ->get();
        $timelengthPerf = DB::table('bookings')
            ->selectRaw('bookings.timeLength as length, SUM(timeLength*bookingPrice) as total')
            ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
            ->groupBy('bookings.timelength')
            ->get();

        return view('livewire.sales-report-performance-category', [
            'period' => $period,
            'ratesPerf' => $ratesPerf,
            'timelengthPerf' => $timelengthPerf,
            'timeslotPerf' => $timeslotPerf,
        ]);
    }
}
