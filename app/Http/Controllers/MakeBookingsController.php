<?php

namespace App\Http\Controllers;

use App\Models\Features;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Operation;
use App\Models\Rates;

class MakeBookingsController extends Controller
{

    function __construct()
    {

        $this -> middleware(['auth']);

    }

    function view_court ()
    {

        // get operation hours
        $start_time = Operation::where('attr', 'start_time') -> first() -> value;
        $end_time = Operation::where('attr', 'end_time') -> first() -> value;

        return view ('customer.book-court', ['selectedDate' => 0, 'start_time' => $start_time, 'end_time' => $end_time]);

    }

    function book_court (Request $request)
    {

        date_default_timezone_set("Asia/Kuala_Lumpur");
        $start_time = Operation::where('attr', 'start_time') -> first() -> value;
        $end_time = Operation::where('attr', 'end_time') -> first() -> value;

        if (isset($_POST["searchForAvailability"]) && isset($_POST["dateSlot"]) && isset($_POST["timeSlot"]) && isset($_POST["timeLength"]))
        {

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

            if (($dateSlot == date("Y-m-d") && $timeSlot >= date("H") && ($timeLength + $timeSlot) <= $end_time))
            {

                // if selected date is today

                $count = DB::table('bookings')
                    ->where('dateSlot', date('Ymd'))
                    ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength)])
                    ->count();

            } else if ($dateSlot > date("Y-m-d") && ($timeLength + $timeSlot) <= $end_time) {

                // if selected date is after today

                $count = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where(
                        function($query) use ($timeSlot, $timeLength){
                            $query
                                ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                        })
                    ->count();

                if ($count != 9) {
                    $courts = array();
                    for ($courtNo = 1; $courtNo <= 9; $courtNo++) {
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

                    $dayOfWeek = date_format(date_create($dateSlot), 'N');
                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRate = "Weekend";
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRate = "Weekdays";
                    }

                    if (Features::where('name', 'rates')->first()->value == 1) {
                        $rates = Rates::where('rateStatus', 1)->get()->where('rateName', '!=', $excludeRate)->where('id', '!=', 3);
                    } else {
                        $rates = Rates::where('id', 3)->get();
                    }


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
                ]);

            } else {
                return redirect() -> route('book-court');
            }

        } else if (isset($_POST['confirm-booking'])) {

            if (Features::where('name', 'rates')->first()->value == 1) {
            // rates is enabled

                // input validation
                $this->validate($request, [

                    'timeSlot' => 'required | digits_between:1,2',
                    'timeLength' => 'required | digits_between:1,2',
                    'dateSlot' => 'required | date_format:Y-m-d',
                    'rateID' => 'required | numeric',

                ]);

                // importing variable
                $dateSlot = str_replace("-", "", $request->dateSlot);
                $timeSlot = $request->timeSlot;
                $timeLength = $request->timeLength;
                $courtID = $request->courtID;
                $rateID = $request->rateID;
                $bookingPrice = $request->bookingPrice;

                $count = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where('timeSlot', $timeSlot)
                    ->where('courtID', $courtID)
                    ->count();

                $dayOfWeek = date_format(date_create($dateSlot), 'N');
                if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                    $excludeRate = "Weekend";
                } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                    $excludeRate = "Weekdays";
                }

                if ($rateID != 3 // not standard rate
                    && Rates::where('id', $rateID)->first()->rateName != $excludeRate // does not include excluded rate for the selected date
                    && Rates::where('id', $rateID)->first()->rateStatus == 1 // does not include rates that are disabled/archived
                    && $count == 0
                    && (($dateSlot == date("Y-m-d") && $timeSlot >= date("H")
                    && ($timeLength + $timeSlot) <= $end_time) || ($dateSlot > date("Y-m-d")
                    && ($timeLength + $timeSlot) <= $end_time))) {

                    // verify once again the booking details before storing in database
                    DB::table('bookings')->insert([
                        'created_at' => date('Y-m-d H:m:s'),
                        'custID' => Auth::user()->id,
                        'courtID' => $courtID,
                        'dateSlot' => $dateSlot,
                        'timeSlot' => $timeSlot,
                        'timeLength' => $timeLength,
                        'rateID' => $rateID,
                        'bookingPrice' => $bookingPrice,
                    ]);

                    return redirect() -> route('mybookings');

                } else {

                    $dayOfWeek = date_format(date_create($dateSlot), 'N');
                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRate = "Weekend";
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRate = "Weekdays";
                    }

                    $rates = Rates::where('rateStatus', 1)->get()->where('rateName', '!=', $excludeRate)->where('id', '!=', 3);

                    $courts = array();
                    for ($courtNo = 1; $courtNo <= 9; $courtNo++) {
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

                    // not standard rate || does not include excluded rate for the selected date || does not include rates that are disabled/archived
                    if ($rateID == 3 || Rates::where('id', $rateID)->first()->rateName == $excludeRate || Rates::where('id', $rateID)->first()->rateStatus == 1){
                        $message = "We are sorry. The rate selected was no longer available. ";
                    } else {
                        $message = "We are sorry. The court that you selected has been booked a moment ago. Please select another court. ";
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
                    ]);

                }

            } else {
            // rates is disabled

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

                $count = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where('timeSlot', $timeSlot)
                    ->where('courtID', $courtID)
                    ->count();

                $rateID = Rates::where('id', 3)->first()->id;
                $bookingPrice = Rates::where('id', 3)->first()->ratePrice;

                if ($request->rateID == null // no rate is selected prior to submission
                    && $count == 0
                    && (($dateSlot == date("Y-m-d") && $timeSlot >= date("H")
                    && ($timeLength + $timeSlot) <= $end_time) || ($dateSlot > date("Y-m-d")
                    && ($timeLength + $timeSlot) <= $end_time))) {

                    // verify once again the booking details before storing in database
                    DB::table('bookings')->insert([
                        'created_at' => date('Y-m-d H:m:s'),
                        'custID' => Auth::user()->id,
                        'courtID' => $courtID,
                        'dateSlot' => $dateSlot,
                        'timeSlot' => $timeSlot,
                        'timeLength' => $timeLength,
                        'rateID' => $rateID,
                        'bookingPrice' => $bookingPrice,
                    ]);

                    return redirect() -> route('mybookings');

                } else {

                    $rates = Rates::where('id', 3)->get();

                    $courts = array();
                    for ($courtNo = 1; $courtNo <= 9; $courtNo++) {
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

                    $message = ($request->rateID != null) ? "We are sorry. We had moved to a single rate policy. Please review the new rate below. " : "We are sorry. The court that you selected has been booked a moment ago. Please select another court. ";

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
                    ]);

                }

            }

        } else {
            return redirect() -> route('book-court');
        }
    }
}
