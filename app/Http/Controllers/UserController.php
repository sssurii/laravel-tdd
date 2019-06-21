<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected function register(Request $request)
    {
        $input = $request->all();
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
        ]);

        if (!empty($user->getErrors())) {
            $view = view('register')->withErrors($user->getErrors());
            return response($view, 400);
        }

        $data = ['success' => true, 'message' => 'Successful registration'];
        return response()->view('register', $data, 201);
    }

    protected function login(Request $request)
    {
        $input = $request->all();
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $view = view('login')->withErrors($validator->errors());
            return response($view, 400);
        }

        $data = ['success' => true, 'message' => 'Welcome'];
        return response()->view('login', $data, 200);
    }
}
