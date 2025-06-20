<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            switch ($user->vai_tro_id) {
                case 1:
                case 2:
                case 3:
                    return redirect()->route('admin.thong-ke.index');
                case 5:
                    return redirect()->route('home');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Vai trò không hợp lệ']);
            }
        }

        return back()->withErrors(['email' => 'Đăng nhập thất bại'])->withInput();
    }

    public function register(RegisterRequest $request)
    {
        session()->flash('active_tab', 'register');

        $validated = $request->validated();

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => bcrypt($validated['password']),
            'dob'         => $validated['dob'] ?? null,
            'phone'       => $validated['phone'] ?? null,
            'username'    => $validated['username'] ?? null,
            'vai_tro_id'  => 5,
            'trang_thai'  => 'active',
            'hoat_dong'   => 1,
        ]);

        Auth::login($user);

        return redirect()->route('login.form');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function forgotPassForm()
    {
        return view('client.forgot-pass');
    }

    public function forgotPass(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);

        $user = User::where('email', $request->email)->first();

        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        Mail::to($user->email)->send(new ForgotPasswordMail($user, $newPassword));

        return back()->with('success', 'Mật khẩu mới đã được gửi đến email của bạn!');
    }
}
