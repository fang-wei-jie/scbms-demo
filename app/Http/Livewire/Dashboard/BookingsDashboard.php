<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Support\Arr;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class BookingsDashboard extends Component
{
    public $date = "";


    public function render()
    {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        $date = $this->date != "" ?  $this->date : date('Y-m-d');

        $bookings = DB::table('bookings')
            ->leftJoin('users', 'users.id', '=', 'bookings.custID')
            ->where('dateSlot', '=', str_replace("-", "", $date))
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->select('bookings.*', 'users.name as username', 'users.phone as phone', 'users.email as email', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
            ->where('status_id', '!=', 0)
            ->orderBy('timeSlot');

        // normal start end time
        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');

        // start end time of the day's booking (just in case if previous operation time was modified)
        $bookingTime = DB::table('bookings')
            ->where('dateSlot', '=', str_replace("-", "", $date))
            ->selectRaw('min(bookings.timeSlot) as startSlot, max(bookings.timeSlot + bookings.timeLength) as endSlot')
            ->first();

        $earliest_booking = $bookingTime->startSlot;
        $last_booking = $bookingTime->endSlot;

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
        $lastCourtIDinTable = DB::table('bookings')->where('dateSlot', '=', str_replace("-", "", $date))->max('courtID');
        $courtCountInOperation = (int) $settings->get('courts_count');
        $courts = ($lastCourtIDinTable > $courtCountInOperation) ? $lastCourtIDinTable : $courtCountInOperation;

        // Get Dates Of Conflicting Bookings Found
        // The following code is modified from SettingsController.php, it contains reduced instructions
        // query for bookings that conflicts with new operation hours
        $operationHourConflicts = DB::table('bookings')
            ->select('dateSlot')
            ->where(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '>', date('Ymd')) // for bookings after today
                    ->where(function($query) use ($start_time, $end_time){
                        $query
                            ->where('timeSlot', '<', $start_time) // for bookings that starts earlier than new start time
                            ->orWhere('timeSlot', '>', $end_time) // for bookings that starts later than new end time
                            ->orWhereRaw('(timeSlot + timeLength - 1) >= '. $end_time); // for bookings that ends later than new end time
                    });
                })
            ->orWhere(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '=', date('Ymd')) // for bookings today
                    ->where('timeSlot', '>', date('H')) // for bookings that had not started, excluding the current hour
                    ->where(function($query) use ($start_time, $end_time){
                        $query
                            ->where('timeSlot', '<', $start_time) // for bookings that starts earlier than new start time
                            ->orWhere('timeSlot', '>=', $end_time) // for bookings that starts later or same as the new end time
                            ->orWhereRaw('(timeSlot + timeLength - 1) >= '. $end_time); // for bookings that ends later than new end time
                    });
                })
            ->where('status_id', 1) // paid bookings only
            ->get();

        // query for bookings that conflicts with new number of courts
        $courtCountConflicts = DB::table('bookings')
            ->select('dateSlot')
            ->where(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '>', date('Ymd')) // for bookings after today
                    ->orWhere(function($query) use ($start_time, $end_time){
                        $query
                            ->where('dateSlot', '=', date('Ymd')) // for bookings today
                            ->where('timeSlot', '>', date('H')); // for bookings that had not started, excluding the current hour
                        });
                })
            ->where('status_id', 1) // paid bookings only
            ->where('courtID', '>', $settings->get('courts_count')) // for bookings with court number bigger than this
            ->get();

        // sort array (merge both query and make the result unique)
        $conflictDates = Arr::sort($operationHourConflicts->merge($courtCountConflicts)->unique()->all());

        return view('livewire.dashboard.bookings-dashboard', [
            "start" => $start,
            "end" => $end,
            "courts" => $courts,

            "conflict_dates" => $conflictDates,

            "real_start" => $start_time,
            "real_end" => $end_time,
            "real_courts" => $courtCountInOperation,

            "date" => $date,
            "staffcancelable" => $settings->get('staff_cancel_booking'),
            
            "bookings" => $bookings->get(),
        ]);
    }
}
