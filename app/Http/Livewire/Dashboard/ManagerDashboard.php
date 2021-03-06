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

        // get setting values
        $settings = Valuestore::make(storage_path('app/settings.json'));

        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');

        // query for bookings that conflicts with new operation hours
        $operationHourConflicts = DB::table('bookings')
            ->where(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '>', date('Ymd')) // for bookings after today
                    ->where(function($query) use ($start_time, $end_time){
                        $query
                            ->where('timeSlot', '<', $start_time) // for bookings that starts earlier than new start time
                            ->orWhere('timeSlot', '>', $end_time) // for bookings that starts later than new end time
                            ->orWhereRaw('(timeSlot + timeLength - 1) >= '. $end_time); // for bookings that ends later than new end time
                    });
                })
            ->orWhere(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '=', date('Ymd')) // for bookings today
                    ->where('timeSlot', '>', date('H')) // for bookings that had not started, excluding the current hour
                    ->where(function($query) use ($start_time, $end_time){
                        $query
                            ->where('timeSlot', '<', $start_time) // for bookings that starts earlier than new start time
                            ->orWhere('timeSlot', '>=', $end_time) // for bookings that starts later or same as the new end time
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
            ->where(function($query) use ($start_time, $end_time){
                $query
                    ->where('dateSlot', '>', date('Ymd')) // for bookings after today
                    ->orWhere(function($query) use ($start_time, $end_time){
                        $query
                            ->where('dateSlot', '=', date('Ymd')) // for bookings today
                            ->where('timeSlot', '>', date('H')); // for bookings that had not started, excluding the current hour
                        });
                })
            ->where('status_id', 1) // paid bookings only
            ->where('courtID', '>', $settings->get('courts_count')) // for bookings with court number bigger than this
            ->orderBy('dateSlot')
            ->orderBy('timeSlot')
            ->orderBy('courtID')
            ->get();

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

        $rates = Rates::where('status', 1)->get();

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

        return view('livewire.dashboard.manager-dashboard', [
            'settings' => $settings,
            'operationHourConflicts' => $operationHourConflicts,
            'courtCountConflicts' => $courtCountConflicts,

            'bookings' => $bookingRows,

            'rates' => $rates,

            'yearSales' => $yearSales->yearSales,
            'monthSales' => $monthSales->monthSales,
            'todaySales' => $todaySales->todaySales,
        ]);
    }
}
