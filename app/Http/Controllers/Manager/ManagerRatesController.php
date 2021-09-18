<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerRatesController extends Controller
{

    function __construct()
    {

        $this->middleware('auth:manager');
    }

    function view()
    {

        $rates = DB::table('rates')
            ->where('rateStatus', '!=', 2)
            ->get();

        return view('manager.rates', ['rates' => $rates]);
    }

    function process(Request $request)
    {

        if (isset($_POST['enable'])) {

            DB::table('rates')
                ->where('id', $request->id)
                ->update(['rateStatus' => 1]);

        } else if (isset($_POST['disable'])) {

            DB::table('rates')
                ->where('id', $request->id)
                ->update(['rateStatus' => 0]);

        } else if (isset($_POST['edit'])) {

            if ($request->oldRateName == $request->rateName) {

                $this -> validate($request, [

                    'ratePrice' => 'required | numeric | max:99'

                ]);

                DB::table('rates')
                    ->where('id', '=', $request->id)
                    ->update(['ratePrice' => $request->ratePrice]);

            } else {

                $this -> validate($request, [

                    'rateName' => 'required | string | max:255 | unique:rates,rateName',
                    'ratePrice' => 'required | numeric | max:99'

                ]);

                DB::table('rates')
                    ->where('id', '=', $request->id)
                    ->update(
                        [
                            'rateName' => $request->input('rateName'),
                            'ratePrice' => $request->input('ratePrice')
                        ]);

            }

        } else if (isset($_POST['archive'])) {

            DB::table('rates')
                ->where('id', '=', $request->id)
                ->update(['rateStatus' => 2]);

        } else if (isset($_POST['add'])) {

            $this -> validate($request, [

                'rateStatus' => 'required | regex:/^[0-1]{1}/u',
                'rateName' => 'required | max:255 | unique:rates,rateName',
                'ratePrice' => 'required | max:99 | numeric',

            ]);

            DB::table('rates')->insert([

                'rateStatus' => $request->rateStatus,
                'rateName' => $request->rateName,
                'ratePrice' => $request->ratePrice,

            ]);

        }

        return redirect() -> route('manager.rates');

    }
}
