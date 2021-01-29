<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth');

    }

    function view (Request $request)
    {

        $this -> validate($request, [

            'bookID' => 'required | regex:/^[0-9]{7}/u',

        ]);

        $invoiceDetail = DB::table('bookings')
            -> join('users', 'bookings.custID', '=', 'users.id')
            -> join('rates', 'bookings.rateID', '=', 'rates.rateID')
            -> where('bookings.bookingID', $request->input('bookID'))
            -> select('bookingID', 'custID', 'bookings.created_at as bookingDateTime', 'name', 'phone', 'email', 'dateSlot', 'timeSlot', 'timeLength', 'courtID', 'rateName', 'ratePrice')
            -> get();

        return view('customer.invoice', ['invoiceDetail' => $invoiceDetail]);

    }

}
