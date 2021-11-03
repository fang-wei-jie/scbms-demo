<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redirect;
use Response;

class MyBookingsController extends Controller
{

    function __construct()
    {

        $this->middleware(['auth']);
    }

    function view_bookings (Request $request)
    {

        // Count if current customer has any bookings
        $count = DB::table('bookings')
            ->where('bookings.custID', '=', Auth::user()->id)
            ->count();

        // if current user has bookings, find them, else, skip them
        if ($count != 0) {
    
            // Query Bookings for current customer
            $today_bookings = DB::table('bookings')
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
            
            // Assynchronously get data for past bookings
            $past_bookings = DB::table('bookings')
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
                ->paginate(25);
    
            $past = '';
            if ($request->ajax()) {
                foreach ($past_bookings as $result) {
                    $condition = ($result->condition) ? $result->condition : 'No condition to follow. ';
    
                    $past.='
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordian'.$result->bookingID.'" aria-expanded="true" aria-controls="accordian'.$result->bookingID.'">
                                <div class="col">
                                    '.substr($result->dateSlot, 6, 2).'/'.substr($result->dateSlot, 4, 2).'/'.substr($result->dateSlot, 0, 4).'
                                    '.$result->timeSlot.':00 - '.($result->timeSlot + $result->timeLength).':00
                                    <br>
                                    Court '.$result->courtID.' - '.$result->bookingRateName.' rate
                                </div>
                                <div class="col-auto me-3">
                                    <h4><span class="badge bg-secondary">Used</span></h4>
                                </div>
                            </button>
                        </h2>
                        <div id="accordian'.$result->bookingID.'" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordian">
                            <div class="accordion-body">
                                <div class="row align-items-center">
                                    
                                    <div class="col">
                                        <div class="row">
                                            <b>'.$result->bookingRateName.' rate</b>
                                        </div>
                                        
                                        <div class="row">
                                            <span>'. $condition .'</span>
                                        </div>
    
                                    </div>
    
                                    <div class="col-auto">
                                        <form action="'.route("view-receipt").'" method="get">
                                            <input type="text" name="bookID" id="bookID" value="'.str_pad($result->bookingID, 7, 0, STR_PAD_LEFT).'" hidden>
                                            <button type="submit" class="btn btn-outline-secondary" id="show-receipt">
                                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                                    <i class="bi bi-receipt"></i>&nbsp;Receipt
                                                </span>
                                            </button>
                                        </form>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                return $past;
            }

            return view('customer.mybookings', ['bookings_count' => $count,'today_bookings' => $today_bookings]);
        
        } else {

            return view('customer.mybookings', ['bookings_count' => 0]);

        }

    }

    function delete_bookings(Request $request)
    {
        if (isset($_POST['delete-booking'])){

            // dd($request->input());

            // input validation
            $this->validate($request, [

                'bookingID' => 'required | numeric',

            ]);

            DB::table('bookings')
                ->where('bookingID', $request->input('bookingID'))
                ->delete();

        }

        return redirect() -> route('mybookings');

    }

}
