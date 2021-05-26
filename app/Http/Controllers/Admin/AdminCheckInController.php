<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCheckInController extends Controller
{

    function __construct()
    {

        $this->middleware('auth:admin');
    }

    function view()
    {

        return view('admin.checkin');
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

        return view('admin.checkin_result', ['resultToQuery' => $request->resultToQuery, 'currentTime' => date('H'), 'currentDate' => date('Ymd'), 'result' => $result]);
    }
}
