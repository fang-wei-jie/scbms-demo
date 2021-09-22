<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreferencesController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view () {

        $name = DB::table('operation_preferences')->where('attr', 'name')->first();
        $domain = DB::table('operation_preferences')->where('attr', 'domain')->first();
        $start_time = DB::table('operation_preferences')->where('attr', 'start_time')->first();
        $end_time = DB::table('operation_preferences')->where('attr', 'end_time')->first();
        $ui = DB::table('ui_preferences')->get();

        $deleteBooking = DB::table('features_preferences')->where('name', 'delete_booking')->first();
        $customerDeleteBooking = DB::table('features_preferences')->where('name', 'customer_delete')->first();
        $adminDeleteBooking = DB::table('features_preferences')->where('name', 'admin_delete')->first();
        $adminRole = DB::table('features_preferences')->where('name', 'admin_role')->first();
        $adminSalesReport = DB::table('features_preferences')->where('name', 'admin_sales_report')->first();
        $rate = DB::table('features_preferences')->where('name', 'rates')->first();
        $weekdayWeekend = DB::table('features_preferences')->where('name', 'rates_weekend_weekday')->first();
        $adminRates = DB::table('features_preferences')->where('name', 'rates_editable_admin')->first();


        $customerUI = DB::table('ui_preferences')->where('side', '')->first();
        $adminUI = DB::table('ui_preferences')->where('side', 'admin')->first();
        $managerUI = DB::table('ui_preferences')->where('side', 'manager')->first();

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

            DB::table('operation_preferences')->where('attr', 'name')->update(['value' => $request->name]);
            DB::table('operation_preferences')->where('attr', 'domain')->update(['value' => $request->domain]);
            DB::table('operation_preferences')->where('attr', 'start_time')->update(['value' => $request->start_time]);
            DB::table('operation_preferences')->where('attr', 'end_time')->update(['value' => $request->end_time]);

            DB::table('features_preferences')->where('name', 'delete_booking')->update(['value' => $request->deleteBooking == null ? '0' : '1']);
            DB::table('features_preferences')->where('name', 'customer_delete')->update(['value' => $request->customerDeleteBooking == null ? '0' : '1']);
            DB::table('features_preferences')->where('name', 'admin_delete')->update(['value' => $request->adminDeleteBooking == null ? '0' : '1']);
            DB::table('features_preferences')->where('name', 'admin_role')->update(['value' => $request->adminRole == null ? '0' : '1']);
            DB::table('features_preferences')->where('name', 'admin_sales_report')->update(['value' => $request->adminSalesReport == null ? '0' : '1']);
            DB::table('features_preferences')->where('name', 'rates')->update(['value' => $request->rate == null ? '0' : '1']);
            DB::table('features_preferences')->where('name', 'rates_weekend_weekday')->update(['value' => $request->weekdayWeekend == null ? '0' : '1']);
            DB::table('features_preferences')->where('name', 'rates_editable_admin')->update(['value' => $request->adminRates == null ? '0' : '1']);

            DB::table('ui_preferences')->where('side', '')->update(['navbar_class' => $request->customer_navbar, 'navbar_text_class' => $request->customer_navtext]);
            DB::table('ui_preferences')->where('side', 'admin')->update(['navbar_class' => $request->admin_navbar, 'navbar_text_class' => $request->admin_navtext]);
            DB::table('ui_preferences')->where('side', 'manager')->update(['navbar_class' => $request->manager_navbar, 'navbar_text_class' => $request->manager_navtext]);

        }

        return redirect() -> route('manager.preferences');

    }
}
