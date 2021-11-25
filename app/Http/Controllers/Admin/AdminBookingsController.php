<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

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

    function cancel (Request $request)
    {
        
        // get setting variables
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // if admin is allowed to cancel booking, then proceed
        if ($settings->get('admin_cancel_booking') == 1) {
    
            DB::table('bookings')
                ->where('bookingID', '=', $request->bookingID)
                ->delete();

        }

        return redirect() -> route('admin.bookings');

    }
}
