<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryCard extends Component
{

    // predefined the "model"-able variables
    public $range = "y";
    public $date = "";
    public $month = "";
    public $day = "";

    public function render()
    {
        // get the current year and month
        $year = date("Y");
        $month = date("m");

        // check what value is selected in the "range" field
        switch ($this->range) {
            // range is month
            case 'm':
                $range = "m";
                $dateTrim = "1, 7";
                if ($this->date != "") { $date = $this->date; } else { $date = $year."-".$month; }
                $condition = 'LIKE "' . $date .'%';
                break;

            // range is year
            case 'y':
                $range = "y";
                $dateTrim = "1, 4";
                if ($this->date != "") { $date = $this->date; } else { $date = $year; }
                $condition = 'LIKE "' . $date .'%';
                break;
        }

        // get the latest date for month/year
        $firstDate = DB::table('bookings')
            ->selectRaw("DISTINCT(SUBSTRING(created_at,".$dateTrim.")) as date")
            ->where('status_id', '!=', 0)
            ->orderByDesc("created_at")
            ->first();

        if ($firstDate != null) {
            // if first date does not equal null, it means there are bookings made (new business does not have sales yet)

            // used to handle when the range is changed
            // date will be automatically set to the first row result of dates list
            if ($this->date == ""
                || (Str::length($this->date) == 4 && $this->range =="m"
                || (Str::length($this->date) == 7 && $this->range =="y"))) {
                $condition = 'LIKE "' . $firstDate->date .'%';
            }

            // sales by rates
            $ratesPerf = DB::table('bookings')
                ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
                ->selectRaw('rate_records.name as rate, SUM(timeLength*price) as total')
                ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
                ->where('status_id', '!=', 0)
                ->groupBy('rate')
                ->orderBy('rate')
                ->get();

            // sales by time slot
            $timeslotPerf = DB::table('bookings')
                ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
                ->selectRaw('timeSlot as slot, SUM(timeLength*price) as total')
                ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
                ->where('status_id', '!=', 0)
                ->groupBy('bookings.timeSlot')
                ->orderBy('bookings.timeSlot')
                ->get();

            // sales by time length
            $timelengthPerf = DB::table('bookings')
                ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
                ->selectRaw('bookings.timeLength as length, SUM(timeLength*price) as total')
                ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
                ->where('status_id', '!=', 0)
                ->groupBy('bookings.timelength')
                ->orderBy('bookings.timelength')
                ->get();

            // get the list of dates that can be seelcted (year or month)
            $dates = DB::table('bookings')
                ->selectRaw("DISTINCT(SUBSTRING(created_at,".$dateTrim.")) as date")
                ->orderByDesc("created_at")
                ->get();

            // get the number of bookings
            $count = DB::table('bookings')
                ->whereRaw('`bookings`.`created_at` ' . $condition . '"')
                ->where('status_id', '!=', 0)
                ->count();

            return view('livewire.sales.category-card', [
                'hasData' => true,
                'range' => $range,
                'dates' => $dates,
                'sum' => $ratesPerf->sum("total"),
                'count' => $count,
                'rates' => $ratesPerf,
                'length' => $timelengthPerf,
                'slot' => $timeslotPerf,
                'ratesMax' => $ratesPerf->max("total"),
                'lengthMax' => $timelengthPerf->max("total"),
                'slotMax' => $timeslotPerf->max("total"),
            ]);

        } else {
            // if not bookings were mae, return empty sales

            return view('livewire.sales.category-card', [
                'hasData' => false,
            ]);
        }
    }
}
