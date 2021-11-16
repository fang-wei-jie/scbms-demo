<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\RateRecords;
use Illuminate\Http\Request;
use App\Models\Rates;
use Spatie\Valuestore\Valuestore;

class ManagerRatesController extends Controller
{

    function __construct()
    {

        $this->middleware('auth:manager');
    }

    function view()
    {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        if ($settings->get('rates') != 1) {
            return back();
        }

        if ($settings->get('rates_weekend_weekday') == 1) {
            // if weekend and weekday is in use, disable normal rate
            $default = Rates::get()->where('id', '<=', 2);
        } else {
            // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
            $default = Rates::where('id', 3)->get();
        }

        $custom = Rates::where('id', '>', 3)->get();

        return view('manager.rates', [
            'default' => $default,
            'custom' => $custom,
        ]);
    }

    function process(Request $request)
    {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        if ($settings->get('rates') != 1) {
            return back();
        }

        if (isset($_POST['enable'])) {

            Rates::where('id', $request->id)->update(['status' => 1]);

        } else if (isset($_POST['disable'])) {

            Rates::where('id', $request->id)->update(['status' => 0]);

        } else if (isset($_POST['edit'])) {

            if (Rates::where('id', $request->id)->first()->name == $request->name || $request->id <= 3) {

                $this -> validate($request, [

                    'price' => 'required | numeric | max:99',

                ]);

                Rates::where('id', $request->id)->update([
                    'price' => $request->price,
                    'condition' => $request->condition,
                ]);

                RateRecords::create([
                
                    'name' => $request->name,
                    'rateID' => $request->id,
                    'price' => $request->price,
                    'condition' => $request->condition,
    
                ]);

            } else {

                $this -> validate($request, [

                    'name' => 'required | string | max:255 | unique:rates,name',
                    'price' => 'required | numeric | max:99',

                ]);

                $rate = Rates::where('id', $request->id)->update([

                    'name' => $request->name,
                    'price' => $request->price,
                    'condition' => $request->condition,

                ]);

                RateRecords::create([
                
                    'name' => $request->name,
                    'rateID' => $rate->id,
                    'price' => $request->price,
                    'condition' => $request->condition,
    
                ]);

            }

            Rates::where('id', $request->id)->update(['condition' => $request->condition]);

            return back()->with('info', 'Rate detail for '.$request->name.' is updated. ');

        } else if (isset($_POST['delete'])) {

            Rates::where('id', $request->id)->delete();

            return back()->with('info', 'Rate was deleted. ');

        } else if (isset($_POST['add'])) {

            $this -> validate($request, [

                'name' => 'required | string | max:255 | unique:rates,name',
                'status' => 'required | regex:/^[0-1]{1}/u',
                'price' => 'required | max:99 | numeric',
                'condition' => 'string | nullable',

            ]);

            Rates::create([

                'name' => $request->name,
                'status' => $request->status,
                'price' => $request->price,
                'condition' => $request->condition,

            ]);

            RateRecords::create([
                
                'name' => $request->name,
                'rateID' => $request->id,
                'price' => $request->price,
                'condition' => $request->condition,

            ]);

        }

        return back();

    }
}
