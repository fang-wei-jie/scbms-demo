<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    function __construct()
    {

        $this -> middleware('guest');

    }

    function view () {

        return view ('auth.register');

    }

    function store (Request $request) {

        // sanction and validate user input
        $this -> validate($request, [

            'name' => 'required | max:255 | regex:/^[\w\s-]*$/',
            'phone' => 'required | numeric | digits_between:10,11 | unique:users',
            'email' => 'required | email | max:75 | unique:users',
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

        return redirect() -> route('mybookings');
    }
}
