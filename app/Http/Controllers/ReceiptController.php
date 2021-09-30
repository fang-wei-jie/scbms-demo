<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\UI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth');

    }

    function get_request () {
        return redirect() -> route('mybookings');
    }

    function view (Request $request)
    {

        $this -> validate($request, [

            'bookID' => 'required | regex:/^[0-9]{7}/u',

        ]);

        $companyName = Operation::where('attr', 'name')->first();
        $companyPhone = Operation::where('attr', 'phone')->first();
        $companyAddress = Operation::where('attr', 'address')->first();
        $logo = UI::where('side', '')->first()->logo;

        $invoiceDetail = DB::table('bookings')
            -> join('users', 'bookings.custID', '=', 'users.id')
            -> where('bookings.bookingID', $request->input('bookID'))
            -> where('bookings.custID', Auth::user()->id)
            -> first();

        if ($invoiceDetail != null) {

            return view('customer.invoice', [
                'invoiceDetail' => $invoiceDetail,
                'bookID' => str_pad($invoiceDetail->bookingID, 7, 0, STR_PAD_LEFT).str_pad($invoiceDetail->custID, 7, 0, STR_PAD_LEFT),
                'createdOn' => $invoiceDetail->created_at,
                'custName' => $invoiceDetail->name,
                'custPhone' => $invoiceDetail->phone,
                'custEmail' => $invoiceDetail->email,
                'bookingDateTimeSlot' => substr($invoiceDetail->dateSlot, 6, 2) . "/" . substr($invoiceDetail->dateSlot, 4, 2) . "/" . substr($invoiceDetail->dateSlot, 0, 4) . " " . $invoiceDetail->timeSlot . ":00 - " . ($invoiceDetail->timeSlot + $invoiceDetail->timeLength) . ":00",
                'courtID' => $invoiceDetail->courtID,
                'rateName' => $invoiceDetail->bookingRateName,
                'companyName' => $companyName->value,
                'companyPhone' => $companyPhone->value,
                'companyAddress' => $companyAddress->value,
                'ratePrice' => $invoiceDetail->bookingPrice,
                'timeLength' => $invoiceDetail->timeLength,
                'logo' => $logo,
            ]);

        } else {

            return redirect() -> route('mybookings');

        }
    }
}
