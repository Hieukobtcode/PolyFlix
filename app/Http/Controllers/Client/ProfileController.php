<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CapBacThe;
use App\Models\LichSuDiem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        // Lấy người dùng hiện tại
        $user = Auth::user();

        // Lấy mốc cấp bậc từ bảng cap_bac_thes
        $milestones = CapBacThe::orderBy('tong_chi_tieu', 'asc')
            ->pluck('tong_chi_tieu');

        // Lấy lịch sử điểm của user đang đăng nhập, mới nhất trước, phân trang
        $lichSuDiem = LichSuDiem::where('users_id', $user->id)
            ->orderBy('thoi_gian', 'desc')
            ->paginate(5);

        return view("client.profile", compact('milestones', 'lichSuDiem', 'user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'   => 'required',
            'new_password'       => 'required|min:6',
            'confirm_password'   => 'required|min:6|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            $user->avatar = $path;
            $user->save();

            return response()->json([
                'avatar_url' => asset('storage/' . $path),
                'message'    => 'Cập nhật ảnh đại diện thành công!'
            ]);
        }

        return response()->json(['error' => 'Không có ảnh nào được tải lên.'], 400);
    }
}