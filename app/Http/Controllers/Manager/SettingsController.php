<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view () {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');

        // query for bookings that conflicts with new operation hours
        $operationHourConflicts = DB::table('bookings')
            ->where(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '>=', date('Ymd')) // for bookings after today
                    ->where(function($query) use ($start_time, $end_time){
                        $query
                            ->where('timeSlot', '<', $start_time) // for bookings that is earlier than new start time
                            ->orWhere('timeSlot', '>', $end_time) // for bookings that starts later than new end time
                            ->orWhereRaw('(timeSlot + timeLength - 1) >= '. $end_time); // for bookings that ends later than new end time
                    });
                })
            ->orWhere(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '=', date('Ymd')) // for bookings after today
                    ->where(function($query) use ($start_time, $end_time){
                        $query
                            ->where('timeSlot', '>=', $end_time) // for bookings that starts later or same as the new end time
                            ->orWhereRaw('(timeSlot + timeLength - 1) >= '. $end_time); // for bookings that ends later than new end time
                    });
                })
            ->where('status_id', 1) // paid bookings only
            ->orderBy('dateSlot')
            ->orderBy('timeSlot')
            ->orderBy('courtID')
            ->get();

        // query for bookings that conflicts with new number of courts
        $courtCountConflicts = DB::table('bookings')
            ->where('dateSlot', '=', date('Ymd')) // for bookings on today
            ->where(function($query) use ($end_time){
                $query
                    ->where('timeSlot', '>=', $end_time) // for bookings that starts later or same as the new end time
                    ->orWhereRaw('(timeSlot + timeLength - 1) >= '. $end_time); // for bookings that ends later than new end time
            })
            ->where('courtID', '>', $settings->get('courts_count')) // for bookings with court number bigger than this
            ->orWhere('dateSlot', '>', date('Ymd')) // for bookings after today
            ->where('status_id', 1) // paid bookings only
            ->where('courtID', '>', $settings->get('courts_count')) // for bookings with court number bigger than this
            ->orderBy('dateSlot')
            ->orderBy('timeSlot')
            ->orderBy('courtID')
            ->get();

        return view ('manager.settings', [
            "settings" => $settings,
            'operationHourConflicts' => $operationHourConflicts,
            'courtCountConflicts' => $courtCountConflicts,
        ]);

    }

    function update (Request $request) {

        if (isset ($_POST['save'])) {
            // update setting values

            $this -> validate($request, [
                "name" => 'required | string | regex:/^[\w\s()-]*$/',
                "domain" => 'required | string | regex:/^[\w\s()-]*$/',
                "phone" => 'required | string',
                "address" => 'required | string',
                "map_lat" => ['required', 'string', 'regex:/^[0-9]{1,3}\.[0-9]{7}$/'],
                "map_long" => ['required', 'string', 'regex:/^[0-9]{1,3}\.[0-9]{7}$/'],

                "start_time" => 'required | numeric | digits_between:1,2',
                "end_time" => 'required | numeric | digits_between:1,2',
                "courts_count" => 'required | numeric',

                "prebook_days_ahead" => 'required | numeric | min:1',
                "booking_cut_off_time" => 'required | numeric | min:0 | max:30',
                "precheckin_duration" => 'required | numeric | min:0 | max:30',
                "payment_grace_period" => 'required | numeric | min:5 | max:15',

                "customer_navbar_color" => 'required | string',
                "customer_navtext" => 'required | string',
                "staff_navbar_color" => 'required | string',
                "staff_navtext" => 'required | string',
                "manager_navbar_color" => 'required | string',
                "manager_navtext" => 'required | string',
            ]);

            $settings = Valuestore::make(storage_path('app/settings.json'));
            $settings->put('name', $request->name);
            $settings->put('domain', strtolower($request->domain));
            if ($request->registration != null) {
                $this -> validate($request, [
                    "domain" => 'string | regex:/^[\w\s()-]*$/',
                ]);
                $settings->put('registration', $request->registration);
            } else { $settings->put('registration', null); }
            $settings->put('phone', $request->phone);
            $settings->put('address', $request->address);
            $settings->put('map_lat', $request->map_lat);
            $settings->put('map_long', $request->map_long);

            if (($request->end_time - $request->start_time) > 0) {
                $settings->put('start_time', $request->start_time);
                $settings->put('end_time', $request->end_time);
            }
            $settings->put('courts_count', $request->courts_count);

            $settings->put('prebook_days_ahead', $request->prebook_days_ahead);
            $settings->put('booking_cut_off_time', $request->booking_cut_off_time);
            $settings->put('precheckin_duration', $request->precheckin_duration);
            $settings->put('payment_grace_period', $request->payment_grace_period);

            $settings->put('cancel_booking', $request->cancelBooking == null ? '0' : '1');
            
            $settings->put('staff_role', $request->staffRole == null ? '0' : '1');
            $settings->put('staff_sales_report', $request->staffSalesReport == null ? '0' : '1');
            $settings->put('staff_cancel_booking', $request->staffCancelBooking == null ? '0' : '1');
            $settings->put('rates_editable_staff', $request->staffRates == null ? '0' : '1');

            $settings->put('checkin_terminal', $request->checkin_terminal == null ? '0' : '1');

            if ($request->hasFile('logo')) {
                $this -> validate($request, ["logo" => 'mimes:png | dimensions:width=128,height=128']);

                $file_name = "customer_favicon." . $request->logo->extension();
                $request->logo->move(public_path('favicon'), $file_name);

                $settings->put('navbar_customer_logo', "favicon/".$file_name);
            }

            $settings->put('navbar_customer_color', $request->customer_navbar_color);
            $settings->put('navbar_customer_text_class', $request->customer_navtext);
            $settings->put('navbar_staff_color', $request->staff_navbar_color);
            $settings->put('navbar_staff_text_class', $request->staff_navtext);
            $settings->put('navbar_manager_color', $request->manager_navbar_color);
            $settings->put('navbar_manager_text_class', $request->manager_navtext);

        }

        return redirect() -> route('manager.settings');

    }
}
