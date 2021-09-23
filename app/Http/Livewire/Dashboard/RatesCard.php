<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rates;

class RatesCard extends Component
{
    public function render()
    {

        $ratesEnabled = Rates::where('rateStatus', 1)-> get();

        return view('livewire.dashboard.rates-card', ['ratesEnabled' => $ratesEnabled]);
    }
}
