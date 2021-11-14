<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rates;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class ManagerDashboard extends Component
{
    public function render()
    {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        $ratesEnabled = ($settings->get('rates') == 1) ? true : false;
        $courts_count = $settings->get('courts_count');

        $bookingRows = DB::table('bookings')
            ->where('dateSlot', '=', date('Ymd'))
            ->where('timeSlot', '<=', date('H'))
            ->where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            ->orderBy('courtID', 'asc')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->select('bookings.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
            ->get();

        if ($settings->get('rates_weekend_weekday') == 1) {
            // if weekend and weekday is in use, disable normal rate
            $rates = Rates::where('status', 1)->get()->where('id', '!=', 3);
        } else {
            // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
            $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
        }

        $yearSales = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->selectRaw('SUM(timeLength*price) as yearSales')
            ->where('bookings.created_at', 'LIKE', date('Y').'%')
            ->first();

        $monthSales = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->selectRaw('SUM(timeLength*price) as monthSales')
            ->where('bookings.created_at', 'LIKE', date('Y-m').'%')
            ->first();

        $todaySales = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->selectRaw('SUM(timeLength*price) as todaySales')
            ->where('bookings.created_at', 'LIKE', date('Y-m-d').'%')
            ->first();

        return view('livewire.dashboard.manager-dashboard', [
            'rates_card_enabled' => $ratesEnabled,

            'courts_count' => $courts_count,
            'bookings' => $bookingRows,

            'rates' => $rates,

            'yearSales' => $yearSales->yearSales,
            'monthSales' => $monthSales->monthSales,
            // 'weekSales' => $weekSales->weekSales,
            'todaySales' => $todaySales->todaySales,
            // "week_start_date" => $startDateNumber,
            // "week_end_date" => $endDateNumber,
        ]);
    }
}
