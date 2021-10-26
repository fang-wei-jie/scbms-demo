<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerCheckInController extends Controller
{

    function __construct()
    {

        $this->middleware('auth:manager');
    }

    function view()
    {

        return view('manager.checkin', ['result' => "0"]);
    }

    function check(Request $request)
    {

        $this->validate($request, [

            'resultToQuery' => 'required | numeric | regex:/^[0-9]{14}/u',

        ]);

        $result = DB::table('bookings')
            ->join('users', 'users.id', '=', 'bookings.custID')
            ->where('bookingID', '=', substr($request->input('resultToQuery'), 0, 7))
            ->where('custID', '=', substr($request->input('resultToQuery'), 8, 7))
            ->first();

        $currentTime = date('H');
        $currentDate = date('Ymd');

        if ($result == null) {

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

                // the current check in came too early today
                $cardColor = "info";
                $cardText = "Future Book Slot, Came Too Early";
                $cardIcon = "bi-brightness-alt-high";
            } else if ($currentTime > ($result->timeSlot + $result->timeLength)) {

                // the current check in came too late today
                $cardColor = "warning";
                $cardIcon = "bi-watch";
                $cardText = "Came Too Late, Book Slot Expired";
            } else {

                // the current check in came too late today
                $cardColor = "warning";
                $cardIcon = "bi-watch";
                $cardText = "Came Too Late, Book Slot Expired";
            }
        } else {
            if ($currentDate < $result->dateSlot) {

                // the current check in came too early
                $cardColor = "info";
                $cardText = "Future Book Slot, Came Too Early";
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

        return view('manager.checkin', [
            'resultToQuery' => $request->resultToQuery,
            'result' => $result,
            'cardColor' => $cardColor,
            'cardIcon' => $cardIcon,
            'cardText' => $cardText,
        ]);
    }
}
