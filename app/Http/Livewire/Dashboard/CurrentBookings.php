<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CurrentBookings extends Component
{
    public function render()
    {

        $bookingRows = DB::table('bookings')
            -> join('rates', 'bookings.rateID', '=', 'rates.id')
            -> where('dateSlot', '=', date('Ymd'))
            -> where('timeSlot', '<=', date('H'))
            -> where(DB::raw('(timeSlot + timeLength - 1) '), '>=', date('H'))
            -> orderBy('courtID', 'asc')
            -> get();

        return view('livewire.dashboard.current-bookings', ['bookings' => $bookingRows]);
    }
}
