<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Operation;
use App\Models\Features;
use App\Models\UI;
use App\Models\Rates;
use Illuminate\Support\Facades\Storage;

class PreferencesController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view () {

        $name = Operation::where('attr', 'name')->first();
        $domain = Operation::where('attr', 'domain')->first();
        $start_time = Operation::where('attr', 'start_time')->first();
        $end_time = Operation::where('attr', 'end_time')->first();

        $deleteBooking = Features::where('name', 'delete_booking')->first();
        $customerDeleteBooking = Features::where('name', 'customer_delete')->first();
        $adminDeleteBooking = Features::where('name', 'admin_delete')->first();
        $adminRole = Features::where('name', 'admin_role')->first();
        $adminSalesReport = Features::where('name', 'admin_sales_report')->first();
        $rate = Features::where('name', 'rates')->first();
        $weekdayWeekend = Features::where('name', 'rates_weekend_weekday')->first();
        $adminRates = Features::where('name', 'rates_editable_admin')->first();

        $ratePerHour = Rates::where('id', 3)->first()->ratePrice;

        $customerUI = UI::where('side', '')->first();
        $adminUI = UI::where('side', 'admin')->first();
        $managerUI = UI::where('side', 'manager')->first();

        return view ('manager.preferences', [
            "name" => $name->value,
            "domain" => $domain->value,
            "start_time" => $start_time->value,
            "end_time" => $end_time->value,

            "deleteBooking" => $deleteBooking->value,
            "customerDeleteBooking" => $customerDeleteBooking->value,
            "adminDeleteBooking" => $adminDeleteBooking->value,
            "adminRole" => $adminRole->value,
            "adminSalesReport" => $adminSalesReport->value,
            "rate" => $rate->value,
            "weekdayWeekend" => $weekdayWeekend->value,
            "adminRates" => $adminRates->value,

            "ratePerHour" => $ratePerHour,

            "customerUI" => $customerUI,
            "adminUI" => $adminUI,
            "managerUI" => $managerUI,
        ]);

    }

    function update (Request $request) {

        if (isset ($_POST['save'])) {

            $this -> validate($request, [
                "name" => 'required | string | max:255',
                "domain" => 'required | string | max:255',
                "start_time" => 'required | numeric | digits_between:1,2',
                "end_time" => 'required | numeric | digits_between:1,2',
                "customer_navbar" => 'required | string',
                "customer_navtext" => 'required | string',
                "admin_navbar" => 'required | string',
                "admin_navtext" => 'required | string',
                "manager_navbar" => 'required | string',
                "manager_navtext" => 'required | string',
            ]);

            if ($request->rate == null) {
                $this -> validate($request, [
                    "ratePerHour"  => 'required | numeric | digits_between:1,2',
                ]);

                Rates::where('id', 3)->update(['ratePrice' => $request->ratePerHour]);
            }

            Operation::where('attr', 'name')->update(['value' => $request->name]);
            Operation::where('attr', 'domain')->update(['value' => $request->domain]);
            Operation::where('attr', 'start_time')->update(['value' => $request->start_time]);
            Operation::where('attr', 'end_time')->update(['value' => $request->end_time]);

            Features::where('name', 'delete_booking')->update(['value' => $request->deleteBooking == null ? '0' : '1']);
            Features::where('name', 'customer_delete')->update(['value' => $request->customerDeleteBooking == null ? '0' : '1']);
            Features::where('name', 'admin_delete')->update(['value' => $request->adminDeleteBooking == null ? '0' : '1']);

            Features::where('name', 'admin_role')->update(['value' => $request->adminRole == null ? '0' : '1']);
            Features::where('name', 'admin_sales_report')->update(['value' => $request->adminSalesReport == null ? '0' : '1']);

            Features::where('name', 'rates')->update(['value' => $request->rate == null ? '0' : '1']);
            Features::where('name', 'rates_weekend_weekday')->update(['value' => $request->weekdayWeekend == null ? '0' : '1']);
            Features::where('name', 'rates_editable_admin')->update(['value' => $request->adminRates == null ? '0' : '1']);

            if ($request->hasFile('logo')) {
                $this -> validate($request, ["logo" => 'mimes:jpg,jpeg,png,svg']);

                $file_name = "customer_favicon." . $request->logo->extension();
                $request->logo->move(public_path('favicon'), $file_name);

                UI::where('side', '')->update(['logo' => "favicon/".$file_name]);
            }

            UI::where('side', '')->update([
                'navbar_class' => $request->customer_navbar,
                'navbar_text_class' => $request->customer_navtext,
                'logo_invert' => $request->customer_invert_logo,
            ]);
            UI::where('side', 'admin')->update([
                'navbar_class' => $request->admin_navbar,
                'navbar_text_class' => $request->admin_navtext,
                'logo_invert' => (($request->admin_navtext == "navbar-dark") ? "invert" : "normal"),
            ]);
            UI::where('side', 'manager')->update([
                'navbar_class' => $request->manager_navbar,
                'navbar_text_class' => $request->manager_navtext,
                'logo_invert' => (($request->manager_navtext == "navbar-dark") ? "invert" : "normal"),
            ]);

        }

        return redirect() -> route('manager.preferences');

    }
}
