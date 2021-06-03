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

        return view('manager.checkin', ['result' => null]);
    }

    function check(Request $request)
    {

        $this->validate($request, [

            'resultToQuery' => 'required | numeric | regex:/^[0-9]{14}/u',

        ]);

        $result = DB::table('bookings')
            ->join('rates', 'rates.id', '=', 'bookings.rateID')
            ->join('users', 'users.id', '=', 'bookings.custID')
            ->where('bookingID', '=', substr($request->input('resultToQuery'), 0, 7))
            ->where('custID', '=', substr($request->input('resultToQuery'), 8, 7))
            ->first();

        return view('manager.checkin', ['resultToQuery' => $request->resultToQuery, 'currentTime' => date('H'), 'currentDate' => date('Ymd'), 'result' => $result]);
    }
}
