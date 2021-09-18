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

        $companyName = DB::table('operation_preferences')
            ->where('attr', 'name')
            ->first();

        $logo = DB::table('ui_preferences')
            ->where('side', '')
            ->first()
            ->logo;

        if ($logo == null) { $logo = "https://icons.getbootstrap.com/assets/icons/hexagon-half.svg"; }

        $invoiceDetail = DB::table('bookings')
            -> join('users', 'bookings.custID', '=', 'users.id')
            -> join('rates', 'bookings.rateID', '=', 'rates.id')
            -> where('bookings.bookingID', $request->input('bookID'))
            -> select('bookingID', 'custID', 'bookings.created_at as bookingDateTime', 'name', 'phone', 'email', 'dateSlot', 'timeSlot', 'timeLength', 'courtID', 'bookingPrice', 'rateName')
            -> first();

        return view('customer.invoice', [
            'invoiceDetail' => $invoiceDetail,
            'bookID' => str_pad($invoiceDetail->bookingID, 7, 0, STR_PAD_LEFT).str_pad($invoiceDetail->custID, 7, 0, STR_PAD_LEFT),
            'createdOn' => $invoiceDetail->bookingDateTime,
            'custName' => $invoiceDetail->name,
            'custPhone' => $invoiceDetail->phone,
            'custEmail' => $invoiceDetail->email,
            'bookingDateTimeSlot' => substr($invoiceDetail->dateSlot, 6, 2) . "/" . substr($invoiceDetail->dateSlot, 4, 2) . "/" . substr($invoiceDetail->dateSlot, 0, 4) . " " . $invoiceDetail->timeSlot . ":00 - " . ($invoiceDetail->timeSlot + $invoiceDetail->timeLength) . ":00",
            'courtID' => $invoiceDetail->courtID,
            'rateName' => $invoiceDetail->rateName,
            'companyName' => $companyName->value,
            'ratePrice' => $invoiceDetail->bookingPrice,
            'timeLength' => $invoiceDetail->timeLength,`
            'logo' => $logo,
        ]);

    }

}
