<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    function view () {

        return view ('auth.register');

    }

    function store (Request $request) {

        // sanction and validate user input
        $this -> validate($request, [

            'name' => 'required | max:255',
            'phone' => 'required | min:10 | max:11',
            'email' => 'required | email | max:75',
            'password' => 'required | min:8 | max:50 | confirmed'

        ]);

        // create user
        User::create([

            'name' => $request -> name,
            'phone' => $request -> phone,
            'email' => $request -> email,
            'password' => Hash::make($request -> password),

        ]);

        Auth::attempt($request -> only('email', 'password'));

        return redirect() -> route('/');
    }
}
