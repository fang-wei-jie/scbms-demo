<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MakeBookingsController extends Controller
{

    function __construct()
    {

        $this -> middleware(['auth']);

    }

    function view_court ()
    {

        // get operation hours
        $start_time = DB::table('operation_preferences') -> where('attr', 'start_time') -> first();
        $end_time = DB::table('operation_preferences') -> where('attr', 'end_time') -> first();

        return view ('customer.book-court', ['selectedDate' => 0, 'start_time' => $start_time, 'end_time' => $end_time]);

    }

    function book_court (Request $request)
    {

        date_default_timezone_set("Asia/Kuala_Lumpur");
        $start_time = DB::table('operation_preferences') -> where('attr', 'start_time') -> first();
        $end_time = DB::table('operation_preferences') -> where('attr', 'end_time') -> first();

        if (isset($_POST["searchForAvailability"]) && isset($_POST["dateSlot"]) && isset($_POST["timeSlot"]) && isset($_POST["timeLength"]))
        {

            // input validation
            $this->validate($request, [

                'timeSlot' => 'required | digits_between:1,2',
                'timeLength' => 'required | digits_between:1,2',
                'dateSlot' => 'required | date_format:Y-m-d'

            ]);

            // importing variable
            $dateSlot = str_replace("-", "", $request->input('dateSlot'));
            $timeSlot = $request->input('timeSlot');
            $timeLength = $request->input('timeLength');

            if (($dateSlot == date("Y-m-d") && $timeSlot >= date("H") && ($timeLength + $timeSlot) <= $end_time->value))
            {

                // if selected date is today

                $count = DB::table('bookings')
                    ->where('dateSlot', date('Ymd'))
                    ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength)])
                    ->count();


            } else if ($dateSlot > date("Y-m-d") && ($timeLength + $timeSlot) <= $end_time->value) {

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

                    $rates = DB::table('rates')
                        ->where('rateStatus', 1)
                        ->get();
                }

                return view ('customer.book-court', [
                    'count' => $count,
                    'courts' => $courts,
                    'rates' => $rates,
                    'selectedDate' => 1,
                    'dateSlot' => $request->input('dateSlot'),
                    'timeSlot' => $timeSlot,
                    'timeLength' => $timeLength,
                    'endTime' => $timeSlot + $timeLength,
                ]);

            } else {
                return redirect() -> route('book-court');
            }

        } else if (isset($_POST['confirm-booking'])) {

            // input validation
            $this->validate($request, [

                'timeSlot' => 'required | digits_between:1,2',
                'timeLength' => 'required | digits_between:1,2',
                'dateSlot' => 'required | date_format:Y-m-d'

            ]);

            // importing variable
            $dateSlot = str_replace("-", "", $request->input('dateSlot'));
            $timeSlot = $request->input('timeSlot');
            $timeLength = $request->input('timeLength');
            $courtID = $request->input('courtID');
            $rateID = $request->input('rateID');
            $bookingPrice = $request->input('bookingPrice');

            // verify once again the booking details before storing in database
            if (($dateSlot == date("Y-m-d") && $timeSlot >= date("H") && ($timeLength + $timeSlot) <= $end_time->value) || ($dateSlot > date("Y-m-d") && ($timeLength + $timeSlot) <= $end_time->value)) {
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

                return view ('customer.book-court', [
                    'selectedDate' => 0,
                ]);

            }

        } else {
            return redirect() -> route('book-court');
        }
    }
}
