<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Features;
use App\Models\Rates;

class AdminRatesController extends Controller
{

    function __construct()
    {

        $this->middleware('auth:admin');
    }

    function view()
    {

        if (Features::where('name', 'rates')->first()->value != 1) {
            return back();
        }

        if (Features::where('name', 'rates_weekend_weekday')->first()->value == 1) {
            // if weekend and weekday is in use, disable normal rate
            $default = Rates::get()->where('id', '<=', 2);
        } else {
            // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
            $default = Rates::where('id', 3)->get();
        }

        $custom = Rates::where('id', '>', 3)->get();

        $editable = Features::where('name', 'rates_editable_admin')->first()->value;

        return view('admin.rates', [
            'default' => $default,
            'custom' => $custom,
            'editable' => $editable,
        ]);
    }

    function process(Request $request)
    {

        if (Features::where('name', 'rates')->first()->value != 1) {
            return back();
        }

        if (Features::where('name', 'rates_editable_admin')->first()->value != 1) {
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

                Rates::where('id', $request->id)->update(['price' => $request->price]);

            } else {

                $this -> validate($request, [

                    'name' => 'required | string | max:255 | unique:rates,name',
                    'price' => 'required | numeric | max:99',

                ]);

                Rates::where('id', $request->id)->update([

                    'name' => $request->name,
                    'price' => $request->price,

                ]);

            }

            return back()->with('info', 'Rate detail for '.$request->name.' is updated. ');

        } else if (isset($_POST['delete'])) {

            Rates::where('id', $request->id)->delete();

            return back()->with('info', 'Rate was deleted. ');

        } else if (isset($_POST['add'])) {
            
            $this -> validate($request, [

                'name' => 'required | string | max:255 | unique:rates,name',
                'status' => 'required | regex:/^[0-1]{1}/u',
                'price' => 'required | max:99 | numeric',

            ]);

            Rates::create([

                'name' => $request->name,
                'status' => $request->status,
                'price' => $request->price,

            ]);

        }

        return back();

    }
}
