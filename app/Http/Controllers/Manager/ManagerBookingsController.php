<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerBookingsController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:manager');
    }

    function view()
    {

        return view('manager.bookings');

    }

    function delete (Request $request)
    {

        DB::table('bookings')
            ->where('bookingID', '=', $request->bookingID)
            ->where('custID', '=', $request->custID)
            ->delete();

        return redirect() -> route('manager.bookings');

    }
}
