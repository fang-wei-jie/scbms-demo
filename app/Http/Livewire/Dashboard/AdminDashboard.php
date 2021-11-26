<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rates;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class AdminDashboard extends Component
{
    public function render()
    {

        // get setting values
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // check if admin is allowed to see the sales report
        $adminSalesReportEnabled = ($settings->get('admin_sales_report') == 1) ? true : false;

        // get list of bookings at the current hour
        $bookingRows = DB::table('bookings')
            ->where('dateSlot', '=', date('Ymd'))
            ->where('timeSlot', '<=', date('H'))
            ->where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            ->orderBy('courtID', 'asc')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->select('bookings.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
            ->where('status_id', '!=', 0)
            ->get();

        if ($settings->get('rates_weekend_weekday') == 1) {
            // if weekend and weekday is in use, disable normal rate
            $rates = Rates::where('status', 1)->get()->where('id', '!=', 3);
        } else {
            // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
            $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
        }

        // get sales metric for differnt time length, this year, this month, today
        $yearSales = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->selectRaw('SUM(timeLength*price) as yearSales')
            ->where('bookings.created_at', 'LIKE', date('Y').'%')
            ->where('status_id', '!=', 0)
            ->first();

        $monthSales = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->selectRaw('SUM(timeLength*price) as monthSales')
            ->where('bookings.created_at', 'LIKE', date('Y-m').'%')
            ->where('status_id', '!=', 0)
            ->first();

        $todaySales = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->selectRaw('SUM(timeLength*price) as todaySales')
            ->where('bookings.created_at', 'LIKE', date('Y-m-d').'%')
            ->where('status_id', '!=', 0)
            ->first();

        return view('livewire.dashboard.admin-dashboard', [
            'sales_card_enabled' => $adminSalesReportEnabled,

            'bookings' => $bookingRows,

            'rates' => $rates,

            'yearSales' => $yearSales->yearSales,
            'monthSales' => $monthSales->monthSales,
            'todaySales' => $todaySales->todaySales,
        ]);
    }
}
