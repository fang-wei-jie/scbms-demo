<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RatesCard extends Component
{
    public function render()
    {

        $ratesEnabled = DB::table('rates')
            -> where('rateStatus', 1)
            -> get();

        return view('livewire.dashboard.rates-card', ['ratesEnabled' => $ratesEnabled]);
    }
}
