<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Rates;
use App\Models\RateRecords;
use Spatie\Valuestore\Valuestore;

class MakeBookingsController extends Controller
{

    function __construct()
    {

        $this -> middleware(['auth']);

    }

    function view ()
    {

        // get setting values
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // get operation hours
        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');

        // get booking grace period
        $booking_cut_off_time = $settings->get('booking_cut_off_time');

        // get calendar date range that the bookings can be made
        $prebook_days_ahead = (int) $settings->get('prebook_days_ahead');
        $minDate = date('Y-m-d');
        $tomorrowDate = date('Y-m-d', strtotime($minDate."+ 1 days"));
        $maxDate = date('Y-m-d', strtotime("+".$prebook_days_ahead." days"));

        return view ('customer.book-court', [
            'selectedDate' => 0,
            'start_time' => $start_time,
            'tomorrow_date' => $tomorrowDate,
            'end_time' => $end_time,
            'booking_cut_off_time' => $booking_cut_off_time,
            'min_date' => $minDate,
            'max_date' => $maxDate,
        ]);

    }

    function check_booking (Request $request)
    {

        // get setting variables
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // get operation hours
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');
        $courts_count = $settings->get('courts_count');

        // get booking grace period
        $booking_cut_off_time = $settings->get('booking_cut_off_time');

        // get calendar date range that the bookings can be made
        $prebook_days_ahead = (int) $settings->get('prebook_days_ahead');
        $minDate = date('Ymd');
        $tomorrowDate = date('Ymd', strtotime($minDate."+ 1 days"));
        $maxDate = date('Ymd', strtotime("+".$prebook_days_ahead." days"));

        // input validation
        $this->validate($request, [

            'timeSlot' => 'required | digits_between:1,2',
            'timeLength' => 'required | digits_between:1,2',
            'dateSlot' => 'required | date_format:Y-m-d'

        ]);

        // importing variable
        $dateSlot = str_replace("-", "", $request->dateSlot);
        $timeSlot = $request->timeSlot;
        $timeLength = $request->timeLength;

        // same day && time slot bigger or equals to current hour && end time is same or lesser than end time
        if (($dateSlot == date("Ymd") && $timeSlot >= date("H") && ($timeLength + $timeSlot) <= $end_time)) {
            // if selected date is today

            if ($timeSlot == date("H") && date("i") > $booking_cut_off_time) {

                return back()->with(['selectedDate' => 0, 'notify' => "We are sorry. You had exceed the last allowed time of booking the court for the selected time. Please choose another time. "]);

            }

        }

        // check if the new operation time, if changed, makes the user submitted timeSlot invalid
        if ($timeSlot < $start_time || ($timeSlot + $timeLength) > $end_time) {
            return back() -> with(['selectedDate' => 0, 'notify' => "We are sorry. Our new operation hours did not match with your selected time. Please choose your new time and date. "]);
        }

        // check if the new allowed days to book ahead, if changed, make the user restart again
        if ($dateSlot > $maxDate) {
            return back() -> with(['selectedDate' => 0, 'notify' => "We are sorry. Our new operation defines that customer can only book " . $prebook_days_ahead . " days ahead. Please choose your new time and date. "]);
        }

        // get array of courts available
        $courts = array();
        for ($courtNo = 1; $courtNo <= $courts_count; $courtNo++) {
            $booked = DB::table('bookings')
                ->where('dateSlot', $dateSlot)
                ->where('courtID', $courtNo)
                ->where(
                    function($query) use ($timeSlot, $timeLength){
                        $query
                            ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                            ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)])
                            ->orWhere(function($query) use ($timeSlot, $timeLength){
                                $query
                                    ->where('timeSlot', '<=', $timeSlot)
                                    ->whereRaw('(timeSlot + timeLength) >= '. $timeSlot + $timeLength);
                            });
                })
                ->count();

            array_push($courts, $booked);
        }

        // check which day of week the date slot is selected
        $dayOfWeek = date_format(date_create($dateSlot), 'N');

        // get list of rates
        $rates = Rates::where('status', 1)->where('dow', 'LIKE', '%'.$dayOfWeek.'%')->get();

        return view ('customer.book-court', [
            'count' => array_count_values($courts)['0'],
            'courts' => $courts,
            'rates' => $rates,
            'selectedDate' => 1,
            'dateSlot' => $request->dateSlot,
            'timeSlot' => $timeSlot,
            'timeLength' => $timeLength,
            'endTime' => $timeSlot + $timeLength,
            'booking_cut_off_time' => $booking_cut_off_time,
        ]);
            
    }

    function confirm_booking (Request $request) {

        // get setting variables
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // input validation
        $this->validate($request, [

            'timeSlot' => 'required | digits_between:1,2',
            'timeLength' => 'required | digits_between:1,2',
            'dateSlot' => 'required | date_format:Y-m-d',
            'courtID' => 'required | numeric',

        ]);

        // importing variable
        $dateSlot = str_replace("-", "", $request->dateSlot);
        $timeSlot = $request->timeSlot;
        $timeLength = $request->timeLength;
        $courtID = $request->courtID;

        // get operation hours
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');
        $courts_count = $settings->get('courts_count');

        // get booking grace period
        $booking_cut_off_time = $settings->get('booking_cut_off_time');

        // get calendar date range that the bookings can be made
        $prebook_days_ahead = (int) $settings->get('prebook_days_ahead');
        $minDate = date('Ymd');
        $tomorrowDate = date('Ymd', strtotime($minDate."+ 1 days"));
        $maxDate = date('Ymd', strtotime("+".$prebook_days_ahead." days"));

        // initialize error message variable
        $message = "";

        // check if user exceeded time for last booking
        if ($dateSlot == date("Ymd") && $timeSlot == date("H") && date("i") > $booking_cut_off_time) {
            return back()->with(['selectedDate' => 0, 'notify' => "We are sorry. You had exceed the last allowed time of booking the court for the selected time. Please choose another time. "]);
        }

        // check if others booked this in advance before you clicked the confirm booking thing
        $availability = DB::table('bookings')
            ->where('dateSlot', $dateSlot)
            ->where('courtID', $courtID)
            ->where(
                function($query) use ($timeSlot, $timeLength){
                    $query
                        ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                        ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)])
                        ->orWhere(function($query) use ($timeSlot, $timeLength){
                            $query
                                ->where('timeSlot', '<=', $timeSlot)
                                ->whereRaw('(timeSlot + timeLength) >= '. $timeSlot + $timeLength);
                        });
            })
            ->count();

        if ($availability != 0) {
            $message .= "We are sorry. The court that you selected has been booked a moment ago. Please select another court. ";
        }

        // check if the number of total courts count had changed, hence making it illogical, where courtID selected is bigger than the total number of counts
        if ($courtID > $courts_count) {
            $message .= "We are sorry. We had updated our number of courts available, and your previous selection was made unavailable. Please select another court. ";
        }

        // check if the new operation time, if changed, makes the user submitted timeSlot invalid
        if ($timeSlot < $start_time || ($timeSlot + $timeLength) > $end_time) {
            return redirect() -> route('book-court') -> with(['selectedDate' => 0, 'notify' => "We are sorry. Our new operation hours did not match with your selected time. Please choose your new time and date. "]);
        }

        // check if the new allowed days to book ahead, if changed, make the user restart again
        if ($dateSlot > $maxDate) {
            return redirect() -> route('book-court') -> with(['selectedDate' => 0, 'notify' => "We are sorry. Our new operation defines that customer can only book " . $prebook_days_ahead . " days ahead. Please choose your new time and date. "]);
        }

        // if there are error messages
        if ($message != "") {

            // count the number of courts available
            $count = DB::table('bookings')
                ->where('dateSlot', $dateSlot)
                ->where('timeSlot', $timeSlot)
                ->where('courtID', $courtID)
                ->count();

            // get the array of courts available
            $courts = array();
            for ($courtNo = 1; $courtNo <= $courts_count; $courtNo++) {
                $booked = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where('courtID', $courtNo)
                    ->where(
                        function($query) use ($timeSlot, $timeLength){
                            $query
                                ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)])
                                ->orWhere(function($query) use ($timeSlot, $timeLength){
                                    $query
                                        ->where('timeSlot', '<=', $timeSlot)
                                        ->whereRaw('(timeSlot + timeLength) >= '. $timeSlot + $timeLength);
                                });
                    })
                    ->count();

                array_push($courts, $booked);
            }

            // check which day of week the date slot is selected
            $dayOfWeek = date_format(date_create($dateSlot), 'N');

            // get list of rates
            $rates = Rates::where('status', 1)->where('dow', 'LIKE', '%'.$dayOfWeek.'%')->get();

            return view ('customer.book-court', [
                'selectedDate' => 1,
                'count' => array_count_values($courts)['0'],
                'courts' => $courts,
                'rates' => $rates,
                'dateSlot' => $request->dateSlot,
                'timeSlot' => $timeSlot,
                'timeLength' => $timeLength,
                'endTime' => $timeSlot + $timeLength,
                'message' => $message,
                'booking_cut_off_time' => $booking_cut_off_time,
            ]);

        }

        // get day of the week
        $dayOfWeek = date_format(date_create($dateSlot), 'N');

        // get the number of courts available
        $count = DB::table('bookings')
            ->where('dateSlot', $dateSlot)
            ->where('timeSlot', $timeSlot)
            ->where('courtID', $courtID)
            ->count();

        // check if the selected date and time is valid
        $validCourtDateTime = ($count < $courts_count
                                && (($dateSlot == date("Ymd") && $timeSlot >= date("H") && ($timeLength + $timeSlot) <= $end_time) ||
                                ($dateSlot > $minDate && $dateSlot <= $maxDate && ($timeLength + $timeSlot) <= $end_time))
                                ) ? true : false;

        $this->validate($request, [
            'rateID' => 'required | numeric',
        ]);
        
        // check which day of week the date slot is selected
        $dayOfWeek = date_format(date_create($dateSlot), 'N');
        
        // get selected rate details
        $rateID = $request->rateID;
        $selectedRate = Rates::where('id', $rateID)->first();

        $validRate = ($selectedRate->status == 1 && str_contains($selectedRate->dow, $dayOfWeek)) ? true : false;

        $validBooking = ($validCourtDateTime && $validRate) ? true : false;

        if ($validBooking) {
            // if booking is valid

            // save created at date temporarily
            $created_at = date('Y-m-d H:i:s');

            // find the id of the selected rate in rate_records table
            $rateRecordID = RateRecords::orderBy('id', 'DESC')->where('rateID', $rateID)->first()->id;

            // insert booking data
            DB::table('bookings')->insert([
                'created_at' => $created_at,
                'custID' => Auth::user()->id,
                'courtID' => $courtID,
                'dateSlot' => $dateSlot,
                'timeSlot' => $timeSlot,
                'timeLength' => $timeLength,
                'rateRecordID' => $rateRecordID,
                'status_id' => 0,
            ]);

            $details = DB::table('bookings')
                ->select('bookings.bookingID')
                ->where('courtID', '=', $courtID)
                ->where('dateSlot', '=', $dateSlot)
                ->where('timeSlot', '=', $timeSlot)
                ->where('timeLength', '=', $timeLength)
                ->where('rateRecordID', '=', $rateRecordID)
                ->where('status_id', '=', 0)
                ->first();

            // return redirect() -> route('mybookings');
            return redirect() -> action([MakeBookingsController::class, 'payment_preview'], ['id' => str_pad($details->bookingID, 7, 0, STR_PAD_LEFT)]);

        } else {
            // if booking is invalid

            // check which day of week the date slot is selected
            $dayOfWeek = date_format(date_create($dateSlot), 'N');

            // get list of rates
            $rates = Rates::where('status', 1)->where('dow', 'LIKE', '%'.$dayOfWeek.'%')->get();

            $message = "We are sorry. The rate selected was no longer available. ";

            // get array of courts available
            $courts = array();
            for ($courtNo = 1; $courtNo <= $courts_count; $courtNo++) {
                $booked = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where('courtID', $courtNo)
                    ->where(
                        function($query) use ($timeSlot, $timeLength){
                            $query
                                ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)])
                                ->orWhere(function($query) use ($timeSlot, $timeLength){
                                    $query
                                        ->where('timeSlot', '<=', $timeSlot)
                                        ->whereRaw('(timeSlot + timeLength) >= '. $timeSlot + $timeLength);
                                });
                    })
                    ->count();

                array_push($courts, $booked);
            }

            return view ('customer.book-court', [
                'selectedDate' => 1,
                'count' => array_count_values($courts)['0'],
                'courts' => $courts,
                'rates' => $rates,
                'dateSlot' => $request->dateSlot,
                'timeSlot' => $timeSlot,
                'timeLength' => $timeLength,
                'endTime' => $timeSlot + $timeLength,
                'message' => $message,
                'booking_cut_off_time' => $booking_cut_off_time,
            ]);

        }
        
        return redirect() -> route('book-court');

    }

    function payment_preview (Request $request) {

        $this -> validate($request, [

            'id' => 'required | regex:/^[0-9]{7}/u',

        ]);

        $details = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->select('bookings.*', 'rate_records.price')
            ->where('bookings.bookingID', '=', $request->id)
            ->first();

        if ($details) {
            if ($details->status_id == 0 && $details->custID == Auth::user()->id) {
            
                return view ('customer.payment', [
                    'id' => $details->bookingID,
                    'amount' => $details->timeLength * $details->price
                ]);
    
            }
        }

        return redirect() -> route('mybookings');

    }

    function payment_process (Request $request) {

        $this -> validate($request, [

            'id' => 'required | regex:/^[0-9]{7}/u',

        ]);

        if (isset($_POST["pay"])) {
            
            DB::table('bookings')
                ->where('bookingID', '=', $request->id)
                ->update(['status_id' => 1]);

        }

        return redirect() -> route('mybookings');

    }
}
