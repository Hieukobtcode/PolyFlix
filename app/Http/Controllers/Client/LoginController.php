<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view()
    {
        return view('client.login');
    }

    public function login(LoginRequest $request)
    {
        dd($request->all());
        $credentials = $request->only('email', 'pass');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['pass']])) {
            return redirect()->route('home'); 
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->withInput();
    }
}
