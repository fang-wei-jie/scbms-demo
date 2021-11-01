<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rates;
use Spatie\Valuestore\Valuestore;

class SettingsController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view () {

        $ratePerHour = Rates::where('id', 3)->first()->price;

        return view ('manager.settings', [
            "rate_per_hour" => $ratePerHour,
            "settings" => Valuestore::make(storage_path('app/settings.json')),
        ]);

    }

    function update (Request $request) {

        if (isset ($_POST['save'])) {

            $this -> validate($request, [
                "name" => 'required | string | max:255',
                "domain" => 'required | string | max:255',
                "phone" => 'required | string',
                "address" => 'required | string',

                "start_time" => 'required | numeric | digits_between:1,2',
                "end_time" => 'required | numeric | digits_between:1,2',
                "courts_count" => 'required | numeric',

                "prebook_days_ahead" => 'required | numeric | min:1',
                "booking_cut_off_time" => 'required | numeric | min:0 | max:30',
                "precheckin_duration" => 'required | numeric | min:0 | max:30',

                "customer_navbar_color" => 'required | string',
                "customer_navtext" => 'required | string',
                "admin_navbar_color" => 'required | string',
                "admin_navtext" => 'required | string',
                "manager_navbar_color" => 'required | string',
                "manager_navtext" => 'required | string',
            ]);

            $settings = Valuestore::make(storage_path('app/settings.json'));
            $settings->put('name', $request->name);
            $settings->put('domain', $request->domain);
            $settings->put('registration', $request->registration);
            $settings->put('phone', $request->phone);
            $settings->put('address', $request->address);

            if ($request->rate == null) {
                $this -> validate($request, [
                    "ratePerHour"  => 'required | numeric | digits_between:1,2',
                ]);

                Rates::where('id', 3)->update(['price' => $request->ratePerHour]);
            }

            if (($request->end_time - $request->start_time) > 0) {
                $settings->put('start_time', $request->start_time);
                $settings->put('end_time', $request->end_time);
            }
            $settings->put('courts_count', $request->courts_count);

            $settings->put('prebook_days_ahead', $request->prebook_days_ahead);
            $settings->put('booking_cut_off_time', $request->booking_cut_off_time);
            $settings->put('precheckin_duration', $request->precheckin_duration);

            $settings->put('delete_booking', $request->deleteBooking == null ? '0' : '1');
            $settings->put('customer_delete_booking', $request->customerDeleteBooking == null ? '0' : '1');
            $settings->put('admin_delete_booking', $request->adminDeleteBooking == null ? '0' : '1');

            $settings->put('admin_role', $request->adminRole == null ? '0' : '1');
            $settings->put('admin_sales_report', $request->adminSalesReport == null ? '0' : '1');

            $settings->put('rates', $request->rate == null ? '0' : '1');
            $settings->put('rates_weekend_weekday', $request->weekdayWeekend == null ? '0' : '1');
            $settings->put('rates_editable_admin', $request->adminRates == null ? '0' : '1');

            if ($request->hasFile('logo')) {
                $this -> validate($request, ["logo" => 'mimes:jpg,jpeg,png,svg']);

                $file_name = "customer_favicon." . $request->logo->extension();
                $request->logo->move(public_path('favicon'), $file_name);

                $settings->put('navbar_customer_logo', "favicon/".$file_name);
            }

            $settings->put('navbar_customer_color', $request->customer_navbar_color);
            $settings->put('navbar_customer_text_class', $request->customer_navtext);
            $settings->put('navbar_admin_color', $request->admin_navbar_color);
            $settings->put('navbar_admin_text_class', $request->admin_navtext);
            $settings->put('navbar_manager_color', $request->manager_navbar_color);
            $settings->put('navbar_manager_text_class', $request->manager_navtext);

        }

        return redirect() -> route('manager.settings');

    }
}
