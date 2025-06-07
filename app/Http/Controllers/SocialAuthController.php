<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt('password_default'), // có thể dùng random
                'avatar' => $googleUser->getAvatar()
            ]
        );

        Auth::login($user);
        return redirect('/'); // hoặc về dashboard
    }

    // Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            // Tìm hoặc tạo mới user trong hệ thống
            $user = User::updateOrCreate([
                'email' => $facebookUser->getEmail(),
            ], [
                'name' => $facebookUser->getName(),
                'facebook_id' => $facebookUser->getId(),
                'password' => bcrypt(Str::random(16)), // mật khẩu giả
                'vai_tro_id' => 5, // mật khẩu giả
            ]);

            // Đăng nhập user
            Auth::login($user);

            // ✅ Chuyển về trang chủ
            return redirect()->route('home');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('login.form')->with('error', 'Đăng nhập Facebook thất bại.');
        }
    }
}
