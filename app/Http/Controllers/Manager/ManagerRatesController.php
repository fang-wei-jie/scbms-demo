<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rates;
use App\Models\Features;

class ManagerRatesController extends Controller
{

    function __construct()
    {

        $this->middleware('auth:manager');
    }

    function view()
    {

        if (Features::where('name', 'rates')->first()->value != 1) {
            return back();
        }

        $rates = Rates::where('rateStatus', '!=', 2)->get()->where('id', '!=', 3);

        return view('manager.rates', ['rates' => $rates]);
    }

    function process(Request $request)
    {

        if (Features::where('name', 'rates')->first()->value != 1) {
            return back();
        }

        if (isset($_POST['enable'])) {

            Rates::where('id', $request->id)->update(['rateStatus' => 1]);

        } else if (isset($_POST['disable'])) {

            Rates::where('id', $request->id)->update(['rateStatus' => 0]);

        } else if (isset($_POST['edit'])) {

            if ($request->oldRateName == $request->rateName) {

                $this -> validate($request, [

                    'ratePrice' => 'required | numeric | max:99'

                ]);

                Rates::where('id', '=', $request->id)->update(['ratePrice' => $request->ratePrice]);

            } else {

                $this -> validate($request, [

                    'rateName' => 'required | string | max:255 | unique:rates,rateName',
                    'ratePrice' => 'required | numeric | max:99'

                ]);

                Rates::where('id', '=', $request->id)->update(
                    [
                        'rateName' => $request->input('rateName'),
                        'ratePrice' => $request->input('ratePrice')
                    ]);

            }

        } else if (isset($_POST['archive'])) {

            Rates::where('id', '=', $request->id)->update(['rateStatus' => 2]);

        } else if (isset($_POST['add'])) {

            $this -> validate($request, [

                'rateStatus' => 'required | regex:/^[0-1]{1}/u',
                'rateName' => 'required | max:255 | unique:rates,rateName',
                'ratePrice' => 'required | max:99 | numeric',

            ]);

            Rates::create([

                'rateStatus' => $request->rateStatus,
                'rateName' => $request->rateName,
                'ratePrice' => $request->ratePrice,

            ]);

        }

        return redirect() -> route('manager.rates');

    }
}
