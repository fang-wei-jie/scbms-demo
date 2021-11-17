<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class AdminCheckInController extends Controller
{

    function __construct()
    {

        $this->middleware('auth:admin');
    }

    function view()
    {

        return view('admin.checkin', ['result' => "0"]);
    }

    function check(Request $request)
    {

        $this->validate($request, [

            'code' => 'required | numeric | regex:/^[0-9]{14}/u',

        ]);

        // query the booking details
        if (substr($request->input('code'), 7, 7) != "0000000") {
            
            $result = DB::table('bookings')
                ->join('users', 'users.id', '=', 'bookings.custID')
                ->where('bookingID', '=', substr($request->input('code'), 0, 7))
                ->where('custID', '=', substr($request->input('code'), 7, 7))
                ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
                ->select('bookings.*', 'users.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
                ->first();
                
        } else {
            
            $result = DB::table('bookings')
                ->where('bookingID', '=', substr($request->input('code'), 0, 7))
                ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
                ->select('bookings.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
                ->first();

        }

        // get current time and date
        $currentMinute = date('i');
        $currentTime = date('H');
        $currentDate = date('Ymd');


        if ($result == null) {

            // if no result was querried
            // invalid booking ID
            $cardColor = "danger";
            $cardIcon = "bi-x-circle-fill";
            $cardText = "Invalid Booking ID";

        } else if ($currentDate == $result->dateSlot) {
            // if date was today

            if ($currentTime >= $result->timeSlot && $currentTime <= ($result->timeSlot + $result->timeLength - 1)) {

                // valid booking
                $cardColor = "success";
                $cardIcon = "bi-check-circle-fill";
                $cardText = "Valid Booking";

            } else if ($currentTime < $result->timeSlot) {

                // get pre-checkin duration set in settings
                $settings = Valuestore::make(storage_path('app/settings.json'));
                $precheckin = $settings->get('precheckin_duration');

                if (60 - $currentMinute <= $precheckin) {

                    // if customer reaches within the pre-checkin duration
                    $cardColor = "success";
                    $cardIcon = "bi-check-circle";
                    $cardText = "Valid Booking on Next Session";
                    
                } else {
    
                    // if customer reaches before the pre-checkin duration
                    // the current check in came too early today
                    $cardColor = "info";
                    $cardText = "Came Too Early";
                    $cardIcon = "bi-brightness-alt-high";

                }

            } else if ($currentTime > ($result->timeSlot + $result->timeLength)) {

                // the current check in came too late today
                $cardColor = "warning";
                $cardIcon = "bi-watch";
                $cardText = "Expired Book Slot";
            } else {

                // the current check in came too late today
                $cardColor = "warning";
                $cardIcon = "bi-watch";
                $cardText = "Expired Book Slot";
            }
        } else {
            if ($currentDate < $result->dateSlot) {

                // the current check in came too early
                $cardColor = "info";
                $cardText = "Future Book Slot";
                $cardIcon = "bi-brightness-alt-high";
            } else if ($currentDate > $result->dateSlot) {

                // the current check in has expired (will be shown when it was older than today)
                $cardColor = "danger";
                $cardIcon = "bi-watch";
                $cardText = "Expired Book Slot";
            } else {

                // error if nothing catches the error
                $cardColor = "danger";
                $cardIcon = "bi-cone-striped";
                $cardText = "System Error";
            }
        }

        return view('admin.checkin', [
            'code' => $request->code,
            'result' => $result,
            'cardColor' => $cardColor,
            'cardIcon' => $cardIcon,
            'cardText' => $cardText,
        ]);
    }
}
