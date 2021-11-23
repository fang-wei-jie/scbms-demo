<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SummaryCard extends Component
{
    public function render()
    {

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

        return view('livewire.sales.summary-card', [
            'yearSales' => $yearSales->yearSales,
            'monthSales' => $monthSales->monthSales,
            'todaySales' => $todaySales->todaySales,
        ]);
    }
}
