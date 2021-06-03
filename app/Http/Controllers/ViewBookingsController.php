<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ViewBookingsController extends Controller
{

    function __construct()
    {

        $this->middleware(['auth']);
    }

    function view_bookings ()
    {

        // Query Bookings for current customer
        $current_bookings = DB::table('bookings')
            ->join('rates', 'bookings.rateID', '=', 'rates.id')
            ->where('bookings.custID', '=', Auth::user()->id)
            ->where(function ($query) {

                $query

                // today valid booking
                -> where (function ($query) {
                    $query
                    ->where('bookings.dateSlot', "=", date('Ymd'))
                    ->where(DB::raw('(bookings.timeSlot + bookings.timeLength)'), '>', date('H'));
                })

                // future
                -> orwhere ('bookings.dateSlot', '>', date('Ymd'));

            })
            ->orderBy('bookings.dateSlot', 'asc')
            ->orderBy('bookings.timeSlot', 'asc')
            ->get();

        $past_bookings = DB::table('bookings')
            ->join('rates', 'bookings.rateID', '=', 'rates.id')
            ->where('bookings.custID', '=', Auth::user()->id)
            ->where(function ($query) {

                $query

                // older than today
                -> where ('bookings.dateSlot', '<', date('Ymd'))

                // older than today's time, hence expired
                -> orwhere (function ($query) {

                    $query
                    -> where ('bookings.dateSlot', '=', date('Ymd'))
                    -> where (DB::raw('(bookings.timeSlot + bookings.timeLength)'), '<=', date('H'));

                });

            })
            ->orderBy('bookings.dateSlot', 'desc')
            ->orderBy('bookings.timeSlot', 'desc')
            ->get();

        return view('customer.mybookings', ['current_bookings' => $current_bookings, 'past_bookings' => $past_bookings]);

    }

}
