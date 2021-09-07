<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreferencesController extends Controller
{
    function __construct()
    {

        $this -> middleware('auth:manager');

    }

    function view () {

        $name = DB::table('operation_preferences')->where('attr', 'name')->first();
        $domain = DB::table('operation_preferences')->where('attr', 'domain')->first();
        $start_time = DB::table('operation_preferences')->where('attr', 'start_time')->first();
        $end_time = DB::table('operation_preferences')->where('attr', 'end_time')->first();
        $operation = DB::table('operation_preferences')->get();
        $ui = DB::table('ui_preferences')->get();
        // $features = DB::table('features_preferences')->get();

        return view ('manager.preferences', [
            "name" => $name->value,
            "domain" => $domain->value,
            "start_time" => $start_time->value,
            "end_time" => $end_time->value,
        ]);

    }

    function update (Request $request) {

        if (isset ($_POST['save'])) {

            $this -> validate($request, [
                "name" => 'required | string | max:255',
                "domain" => 'required | string | max:255',
                "start_time" => 'required | numeric | digits_between:1,2',
                "end_time" => 'required | numeric | digits_between:1,2',
            ]);

            DB::table('operation_preferences')->where('attr', 'name')->update(['value' => $request->name]);
            DB::table('operation_preferences')->where('attr', 'domain')->update(['value' => $request->domain]);
            DB::table('operation_preferences')->where('attr', 'start_time')->update(['value' => $request->start_time]);
            DB::table('operation_preferences')->where('attr', 'end_time')->update(['value' => $request->end_time]);

        }

        return redirect() -> route('manager.preferences');

    }
}
