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

    function book_court (Request $request)
    {

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

        if (isset($_POST["searchForAvailability"])) {

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

                } else {

                    $count = DB::table('bookings')
                        ->where('dateSlot', date('Ymd'))
                        ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength)])
                        ->count();

                }

            } else if ($dateSlot > $minDate && $dateSlot <= $maxDate && ($timeLength + $timeSlot) <= $end_time) {

                // if selected date is after today

                // count numebr of courts
                $count = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where(
                        function($query) use ($timeSlot, $timeLength){
                            $query
                                ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                        })
                    ->count();

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
                                ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                    })
                    ->count();

                array_push($courts, $booked);
            }

            if ($settings->get('rates') == 1) {
                // if rates was enabled

                if ($settings->get('rates_weekend_weekday') == 1) {
                    // weekend and weekday is in use

                    // check which rates to exclude
                    $dayOfWeek = date_format(date_create($dateSlot), 'N');
                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRateID = 2; // weekend
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRateID = 1; // weekdays
                    }

                    // if weekend and weekday is in use, disable normal rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 3)->where('id', '!=', $excludeRateID);
                } else {
                    // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
                }

            } else {
                // if rates was disabled
                $rates = Rates::where('id', 3)->get();
            }

            return view ('customer.book-court', [
                'count' => $count,
                'courts' => $courts,
                'rates' => $rates,
                'selectedDate' => 1,
                'dateSlot' => $request->dateSlot,
                'timeSlot' => $timeSlot,
                'timeLength' => $timeLength,
                'endTime' => $timeSlot + $timeLength,
                'booking_cut_off_time' => $booking_cut_off_time,
            ]);

            return redirect() -> route('book-court');

        } else if (isset($_POST['confirm-booking'])) {

            // check if rates is enabled
            $ratesEnabled = $settings->get('rates') == 1 ? true : false;

            // input validation
            $this->validate($request, [

                'timeSlot' => 'required | digits_between:1,2',
                'timeLength' => 'required | digits_between:1,2',
                'dateSlot' => 'required | date_format:Y-m-d',

            ]);

            // importing variable
            $dateSlot = str_replace("-", "", $request->dateSlot);
            $timeSlot = $request->timeSlot;
            $timeLength = $request->timeLength;
            $courtID = $request->courtID;

            // check if user exceeded time for last booking
            if ($timeSlot == date("H") && date("i") > $booking_cut_off_time) {
                return back()->with(['selectedDate' => 0, 'notify' => "We are sorry. You had exceed the last allowed time of booking the court for the selected time. Please choose another time. "]);
            }

            // if user come from rates disabled to rates enabled
            if ($ratesEnabled && $request->rateID == null) {

                // get day of the week
                $dayOfWeek = date_format(date_create($dateSlot), 'N');

                if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                    $excludeRateID = 2; // weekend
                } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                    $excludeRateID = 1; // weekdays
                }

                if ($settings->get('rates_weekend_weekday') == 1) {
                    // if weekend and weekday is in use, disable normal rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 3)->where('id', '!=', $excludeRateID);
                } else {
                    // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
                }

                $message = "We are sorry. We had moved away from single rate policy. Please review the new rate below. ";

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
                                    ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                        })
                        ->count();

                    array_push($courts, $booked);
                }

                return view ('customer.book-court', [
                    'selectedDate' => 1,
                    'count' => $count,
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

            if ($ratesEnabled) {
                // if rates were in use

                $this->validate($request, [
                    'rateID' => 'required | numeric',
                ]);

                // obtain rates detail
                $rateID = $request->rateID;

                // validate rate validity
                if ($settings->get('rates_weekend_weekday') == 1) {

                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRateID = 2; // weekend
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRateID = 1; // weekdays
                    }

                    // if weekend and weekday IS in use
                    // check if normal rate is NOT selected, then if rate selected is enabled, then if rate selected is NOT excluded rate for the date
                    $validRate = ($rateID != 3 && Rates::where('id', $rateID)->first()->status == 1 && $rateID != $excludeRateID) ? true : false;
                } else {
                    // if weekend and weekday is NOT in use
                    // check if weekend/weekday rate is NOT selected, then if rate selected is enabled
                    $validRate = ($rateID != 1 && $rateID != 2 && Rates::where('id', $rateID)->first()->status == 1) ? true : false;
                }

                $validBooking = ($validCourtDateTime && $validRate) ? true : false;

            } else {

                // inject the one and only rate and get details
                $rateID = 3;

                // check if it was a valid booking
                $validBooking = ($validCourtDateTime && $request->rateID == null) ? true : false;

            }

            if ($validBooking) {
                // if booking is valid

                // save created at date temporarily
                $created_at = date('Y-m-d H:m:s');

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
                ]);

                return redirect() -> route('mybookings');

            } else {
                // if booking is invalid

                if ($ratesEnabled) {
                    // if rates is enabled

                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRateID = 2; // weekend
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRateID = 1; // weekdays
                    }

                    if ($settings->get('rates_weekend_weekday') == 1) {
                        // if weekend and weekday is in use, disable normal rate
                        $rates = Rates::where('status', 1)->get()->where('id', '!=', 3)->where('id', '!=', $excludeRateID);
                    } else {
                        // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
                        $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
                    }

                    $message = ($validRate == false) ? "We are sorry. The rate selected was no longer available. " : "We are sorry. The court that you selected has been booked a moment ago. Please select another court. ";

                } else {
                    // if rates is disabled
                    $rates = Rates::where('id', 3)->get();

                    $message = ($request->rateID != null) ? "We are sorry. We had moved to a single rate policy. Please review the new rate below. " : "We are sorry. The court that you selected has been booked a moment ago. Please select another court. ";

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
                                    ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                        })
                        ->count();

                    array_push($courts, $booked);
                }

                return view ('customer.book-court', [
                    'selectedDate' => 1,
                    'count' => $count,
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

        } else {
            return redirect() -> route('book-court');
        }
    }
}
