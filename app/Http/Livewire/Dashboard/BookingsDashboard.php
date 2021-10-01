<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;

class BookingsDashboard extends Component
{
    public $date = "";


    public function render()
    {

        $date = $this->date != "" ?  $this->date : date('Ymd');

        $bookings = DB::table('bookings')
            ->join('users', 'users.id', '=', 'bookings.custID')
            ->where('dateSlot', '=', str_replace("-", "", $date))
            ->orderBy('timeSlot');

        // normal start end time
        $start_time = Operation::where('attr', 'start_time')->first()->value;
        $end_time = Operation::where('attr', 'end_time')->first()->value;

        // start end time of the day's booking (just in case if previous operation time was modified)
        $earliest_booking = $bookings->min('timeSlot');
        $last_booking = $bookings->max('timeSlot');

        if ($earliest_booking != null || $last_booking != null) {

            // if earliest booking is earlier than start time, make it earliest booking time
            $start = $earliest_booking < $start_time ? $earliest_booking : $start_time;

            // if last booking is later than end time, make it last booking time
            $end = $last_booking > $end_time ? $last_booking : $end_time;

        } else {

            $start = $start_time;
            $end = $end_time;

        }

        // if current court count is smaller than the previous booked court no, use the one in the bookings table
        $lastCourtIDinTable = DB::table('bookings')->max('courtID');
        $courtCountInOperation = Operation::where('attr', 'courts_count')->first()->value;
        $courts = $lastCourtIDinTable > $courtCountInOperation ? $lastCourtIDinTable : $courtCountInOperation;

        return view('livewire.dashboard.bookings-dashboard', [
            "start" => $start,
            "end" => $end,
            "courts" => $courts,
            "date" => substr($date, 0, 4)."-".substr($date, 4, 2)."-".substr($date, 6, 2),

            "bookings" => $bookings->get(),
        ]);
    }
}
