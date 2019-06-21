<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    protected function register(Request $request)
    {
        $input = $request->all();
        $saved  = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
        ]);

        $data = ['success' => true, 'message' => 'Successful registration'];

        return response()->view('register', $data, 201);
    }
}
