<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class StaffBookingsController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:staff');
    }

    function view()
    {

        return view('staff.bookings');

    }

    function cancel (Request $request)
    {
        
        // get setting variables
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // if staff is allowed to cancel booking, then proceed
        if ($settings->get('staff_cancel_booking') == 1) {
    
            DB::table('bookings')
                ->where('bookingID', '=', $request->bookingID)
                ->delete();

        }

        return redirect() -> route('staff.bookings');

    }
}
