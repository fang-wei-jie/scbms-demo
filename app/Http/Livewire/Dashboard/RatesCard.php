<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rates;
use App\Models\Features;

class RatesCard extends Component
{
    public function render()
    {

        if (Features::where('name', 'rates')->first()->value == 1) {

            if (Features::where('name', 'rates_weekend_weekday')->first()->value == 1) {
                // if weekend and weekday is in use, disable normal rate
                $rates = Rates::where('rateStatus', 1)->get()->where('id', '!=', 3);
            } else {
                // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
                $rates = Rates::where('rateStatus', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
            }

            return view('livewire.dashboard.rates-card', ['rates' => $rates, 'enabled' => true]);

        } else {

            return view('livewire.dashboard.rates-card', ['enabled' => false]);

        }
    }
}
