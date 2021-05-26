<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBookingsController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:admin');
    }

    function view()
    {

        $todayBookings = DB::table('bookings')
            ->join('rates', 'rates.id', '=', 'bookings.rateID')
            ->join('users', 'users.id', '=', 'bookings.custID')
            ->where('dateSlot', '=', date('Ymd'))
            ->orderBy('timeSlot')
            ->get();

            $futureBookings = DB::table('bookings')
            ->join('rates', 'rates.id', '=', 'bookings.rateID')
            ->join('users', 'users.id', '=', 'bookings.custID')
            ->where('dateSlot', '>', date('Ymd'))
            ->orderBy('dateSlot')
            ->orderBy('timeSlot')
            ->get();

        $previousBookings = DB::table('bookings')
            ->join('rates', 'rates.id', '=', 'bookings.rateID')
            ->join('users', 'users.id', '=', 'bookings.custID')
            ->where('dateSlot', '<', date('Ymd'))
            ->orderByDesc('dateSlot')
            ->orderByDesc('timeSlot')
            ->get();

        $allBookings = DB::table('bookings')
            ->join('rates', 'rates.id', '=', 'bookings.rateID')
            ->join('users', 'users.id', '=', 'bookings.custID')
            ->orderByDesc('dateSlot')
            ->orderByDesc('timeSlot')
            ->get();

        return view('admin.bookings', ['todayBookings' => $todayBookings, 'futureBookings' => $futureBookings, 'previousBookings' => $previousBookings, 'allBookings' => $allBookings]);

    }

    function delete (Request $request)
    {

        DB::table('bookings')
            ->where('bookingID', '=', $request->bookingID)
            ->where('custID', '=', $request->custID)
            ->delete();

        return redirect() -> route('admin.bookings');

    }
}
