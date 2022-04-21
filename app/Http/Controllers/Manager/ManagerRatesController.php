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

        // get setting values
        $settings = Valuestore::make(storage_path('app/settings.json'));
        
        // get list of default and custom rates
        $rates = Rates::all();
        $default = $rates -> where('id', '<', 4);
        $custom = $rates -> where('id', '>', 3);

        return view('manager.rates', [
            'settings' => $settings,
            'default' => $default,
            'custom' => $custom,
        ]);
    }

    function process(Request $request)
    {
        
        if (isset($_POST['enable'])) {

            Rates::where('id', $request->id)->update(['status' => 1]);

        } else if (isset($_POST['disable'])) {

            Rates::where('id', $request->id)->update(['status' => 0]);

        } else if (isset($_POST['edit'])) {

            if (Rates::where('id', $request->id)->first()->name == $request->name || $request->id <= 3) {
                // for rates that hasn't changed name, or default rates

                $this -> validate($request, [

                    'price' => 'required | numeric | max:99',
                    'condition' => 'string | nullable',

                ]);

                Rates::where('id', $request->id)->update([
                    'price' => $request->price,
                    'condition' => $request->condition,
                ]);

                // create a new record in rate records to store rate detail(s) change
                RateRecords::create([

                    'name' => $request->name,
                    'rateID' => $request->id,
                    'price' => $request->price,
                    'condition' => $request->condition,

                ]);

            } else {
                // for rates that do change name (that excludes default rates)

                $this -> validate($request, [

                    'name' => 'required | string | max:255 | unique:rates,name',
                    'price' => 'required | numeric | max:99',
                    'condition' => 'string | nullable',

                ]);

                Rates::where('id', $request->id)->update([

                    'name' => $request->name,
                    'price' => $request->price,
                    'condition' => $request->condition,

                ]);  

            }

            // update DOW (excluding default rates)
            if ($request->id > 3) {

                $this -> validate($request, [
                    'qdow' => 'required',
                ]);
                
                $qdow = $request->qdow;

                if ($qdow == "1234567" || $qdow == "12345" || $qdow == "67") {
                    // if DOW is selected from quick select, use its value
                    $dow = $qdow;
                } else {
                // if custom DOW is used
                    $dow = "";
                    $dow .= ($request->monday == null) ? "" : "1";
                    $dow .= ($request->tuesday == null) ? "" : "2";
                    $dow .= ($request->wednesday == null) ? "" : "3";
                    $dow .= ($request->thursday == null) ? "" : "4";
                    $dow .= ($request->friday == null) ? "" : "5";
                    $dow .= ($request->saturday == null) ? "" : "6";
                    $dow .= ($request->sunday == null) ? "" : "7";
                }

                Rates::where('id', $request->id)->update([
                    'dow' => $dow,
                ]);

            }

            // if the only thing changed is DOW, then do not create a new rate record
            $current_values = Rates::where('id', $request->id)->first();
            if ($current_values->name != $request->name || $current_values->price != $request->price || $current_values->condition != $request->condition) {

                // create a new record in rate records to store rate detail(s) change
                RateRecords::create([
                
                    'name' => $request->name,
                    'rateID' => $request->id,
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
                'qdow' => 'required',
                'condition' => 'string | nullable',

            ]);

            // process DOW selections
            $qdow = $request->qdow;

            if ($qdow == "1234567" || $qdow == "12345" || $qdow == "67") {
                // if DOW is selected from quick select, use its value
                $dow = $qdow;
            } else {
            // if custom DOW is used
                $dow = "";
                $dow .= ($request->monday == null) ? "" : "1";
                $dow .= ($request->tuesday == null) ? "" : "2";
                $dow .= ($request->wednesday == null) ? "" : "3";
                $dow .= ($request->thursday == null) ? "" : "4";
                $dow .= ($request->friday == null) ? "" : "5";
                $dow .= ($request->saturday == null) ? "" : "6";
                $dow .= ($request->sunday == null) ? "" : "7";
            }

            $rate = Rates::create([

                'name' => $request->name,
                'status' => $request->status,
                'price' => $request->price,
                'dow' => $dow,
                'condition' => $request->condition,

            ]);

            // create a new record in rate records to store rate detail(s)
            RateRecords::create([
                
                'name' => $request->name,
                'rateID' => $rate->id,
                'price' => $request->price,
                'condition' => $request->condition,

            ]);

        }

        return back();

    }

    function update (Request $request) {
        
        // get setting values
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // save rates weekend weekday toggle state
        $settings->put('rates_weekend_weekday', $request->weekdayWeekend == null ? '0' : '1');

        // update status of default rates with the change of weekend weekday toggle
        if ($request->weekdayWeekend == null) {
            Rates::where('id', 3)->update(['status' => 1]);
            Rates::where('id', '<', 3)->update(['status' => 0]);
        } else {
            Rates::where('id', 3)->update(['status' => 0]);
            Rates::where('id', '<', 3)->update(['status' => 1]);
        }

        return back();

    }
    
}
