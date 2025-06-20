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
use App\Mail\VerifyEmailOTP;
use Illuminate\Support\Str;


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
                    return redirect()->route('home')->with('success',"Đăng nhập thành công");
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Vai trò không hợp lệ']);
            }
        }

        return back()->withErrors(['email' => 'Đăng nhập thất bại'])->withInput();
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $otp = random_int(100000, 999999);

        session([
            'register_data' => [
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => bcrypt($validated['password']),
                'dob'      => $validated['dob'] ?? null,
                'phone'    => $validated['phone'] ?? null,
                'username' => $validated['username'] ?? null,
            ],
            'register_otp' => $otp
        ]);

        Mail::to($validated['email'])->send(new VerifyEmailOTP($otp));

        return redirect()->route('verify.form')->with('message', 'Vui lòng kiểm tra email để xác nhận đăng ký.');
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

    public function showVerifyForm()
    {
        if (!session()->has('register_data')) {
            return redirect()->route('login');
        }

        return view('client.verify');
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        if ($request->otp == session('register_otp')) {
            $data = session('register_data');

            $user = User::create([
                'name'        => $data['name'],
                'email'       => $data['email'],
                'password'    => bcrypt($data['password']),
                'dob'         => $data['dob'] ?? null,
                'phone'       => $data['phone'] ?? null,
                'username'    => $data['username'] ?? null,
                'vai_tro_id'  => 5,
                'trang_thai'  => 'active',
                'hoat_dong'   => 1,
            ]);

            session()->forget(['register_data', 'register_otp']);

            return redirect()->route('login')->with('success', 'Tạo tài khoản thành công!');
        } else {
            return back()->withErrors(['otp' => 'Mã xác nhận không đúng']);
        }
    }
}
