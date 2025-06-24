<?php

namespace App\Http\Controllers\Client;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RapPhim;


class LoginController extends Controller
{
    public function view()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'pass');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['pass']])) {
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->withInput();
    }
}
