<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            switch ($user->vai_tro_id) {
                case 1: return redirect()->route('admin.dashboard');        // Admin tổng
                case 2: return redirect()->route('admin.lien-he.index');       // Admin chi nhánh
                // case 3: return redirect()->route('cinema.dashboard');       // Admin rạp
                // case 4: return redirect()->route('staff.dashboard');        // Nhân viên
                case 5: return redirect()->route('home');            // Người dùng
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Vai trò không hợp lệ']);
            }
        }

        return back()->withErrors(['email' => 'Thông tin không chính xác'])->withInput();
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => bcrypt($validated['password']),
            'vai_tro_id'  => 5, // Mặc định là người dùng
            'trang_thai'  => 'active',
            'hoat_dong'   => 1,
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }
}
