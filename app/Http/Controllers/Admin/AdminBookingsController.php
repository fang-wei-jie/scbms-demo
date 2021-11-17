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

        return view('admin.bookings');

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
