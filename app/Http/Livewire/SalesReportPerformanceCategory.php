<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesReportPerformanceCategory extends Component
{

    public $type = "y";
    public $date = "";
    public $month = "";
    public $day = "";

    public function render()
    {
        $year = date("Y");
        $month = date("m");
        $day = date("d");

        $dayOfWeek = date("w");
        $startDateNumber = str_pad(date("d") - $dayOfWeek, 2, "0", STR_PAD_LEFT);
        $endDateNumber = str_pad(date("d") + (6 - $dayOfWeek), 2, "0", STR_PAD_LEFT);

        switch ($this->type) {
            // case 'd':
            //     $type = "d";
            //     $condition = 'LIKE "' . date("Y-m-d%");
            //     $period = date("d") . " " . $monthString . " " . date("Y");
            //     break;
            // case 'w':
            //     $type = "w";
            //     $condition = 'BETWEEN "' . date('Y-m-') . $startDateNumber . '%" AND "' . date('Y-m-') . $endDateNumber . '%';
            //     $period = $startDateNumber . date('/m/Y') . " till " . $endDateNumber . date('/m/Y');
            //     break;
            case 'm':
                $type = "m";
                $dateTrim = "1, 7";
                if ($this->date != "") { $date = $this->date; } else { $date = $year."-".$month; }
                $condition = 'LIKE "' . $date .'%';
                break;

            case 'y':
                $type = "y";
                $dateTrim = "1, 4";
                if ($this->date != "") { $date = $this->date; } else { $date = $year; }
                $condition = 'LIKE "' . $date .'%';
                break;
        }

        $firstDate = DB::table('bookings')
            ->selectRaw("DISTINCT(SUBSTRING(created_at,".$dateTrim.")) as date")
            ->orderByDesc("created_at")
            ->first();

        if ($firstDate != null) {

            // used to handle when the type is changed
            // date will be automatically set to the first row result of dates list
            if ($this->date == ""
                || (Str::length($this->date) == 4 && $this->type =="m"
                || (Str::length($this->date) == 7 && $this->type =="y"))) {
                $condition = 'LIKE "' . $firstDate->date .'%';
            }

            $ratesPerf = DB::table('bookings')
                ->join('rates', 'rates.id', '=', 'bookings.rateID')
                ->selectRaw('rates.rateName as rate, SUM(timeLength*bookingPrice) as total')
                ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
                ->groupBy('rates.rateName')
                ->orderBy('rates.rateName')
                ->get();

            $timeslotPerf = DB::table('bookings')
                ->selectRaw('timeSlot as slot, SUM(timeLength*bookingPrice) as total')
                ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
                ->groupBy('bookings.timeSlot')
                ->orderBy('bookings.timeSlot')
                ->get();

            $timelengthPerf = DB::table('bookings')
                ->selectRaw('bookings.timeLength as length, SUM(timeLength*bookingPrice) as total')
                ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
                ->groupBy('bookings.timelength')
                ->orderBy('bookings.timelength')
                ->get();

            $dates = DB::table('bookings')
                ->selectRaw("DISTINCT(SUBSTRING(created_at,".$dateTrim.")) as date")
                ->orderByDesc("created_at")
                ->get();

            return view('livewire.sales-report-performance-category', [
                'hasData' => true,
                'type' => $type,
                'rates' => $ratesPerf,
                'ratesMax' => $ratesPerf->max("total"),
                'ratesSum' => $ratesPerf->sum("total"),
                'length' => $timelengthPerf,
                'lengthMax' => $timelengthPerf->max("total"),
                'lengthSum' => $timelengthPerf->sum("total"),
                'slot' => $timeslotPerf,
                'slotMax' => $timeslotPerf->max("total"),
                'slotSum' => $timeslotPerf->sum("total"),
                'dates' => $dates,
            ]);

        } else {
            return view('livewire.sales-report-performance-category', [
                'hasData' => false,
            ]);
        }

    }
}
