<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\MoiQuanLyRap;
use Illuminate\Support\Str;
use App\Models\AdminRequest;
use App\Models\QuanLyInvite;
use Illuminate\Http\Request;
use App\Mail\MoiQuanLyChiNhanh;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;




class InviteController extends Controller
{

    //
    public function sendInvite(Request $request)
    {
        // dd($request);
        $request->validate([
            'email' => 'required|email|unique:quan_ly_invites,email',
            'loai_quan_ly' => 'required|in:1,2', // 1 = chi nhánh, 2 = rạp phim
            'chi_nhanh_id' => 'required_if:loai_quan_ly,1|nullable|exists:chi_nhanhs,id',
            'rap_phim_id' => 'required_if:loai_quan_ly,2|nullable|exists:rap_phims,id',
        ]);
    
        $token = Str::random(40);
    
        $inviteData = [
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addHour(),
            'loai_quan_ly' => $request->loai_quan_ly,
        ];
    
        if ($request->loai_quan_ly == 1) {
            $inviteData['chi_nhanh_id'] = $request->chi_nhanh_id;
        } else {
          
            $inviteData['rap_phim_id'] = $request->rap_phim_id;
        }
        // dd($inviteData);
    
        QuanLyInvite::create($inviteData);
    
        $link = route('invite.form', ['token' => $token]);
    
        if ($request->loai_quan_ly == 1) {
            // dd($link);
            Mail::to($request->email)->queue(new MoiQuanLyChiNhanh($link));
     
        } else {
            Mail::to($request->email)->queue(new MoiQuanLyRap($link));
        }
    
        return back()->with('success', 'Đã gửi lời mời thành công đến ' . $request->email);
    }
    public function showForm(Request $request)
    {
        $token = $request->token;
        $invite = QuanLyInvite::where('token', $token)
            ->where('expires_at', '>', now())
            ->where('used', false)
            ->firstOrFail();

        return view('auth.invite.form', compact('token', 'invite'));
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'name' => 'required',
            'dob' => 'nullable|date',
            'address' => 'nullable',
            'phone' => 'required|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);
    
        $invite = QuanLyInvite::where('token', $request->token)
            ->where('expires_at', '>', now())
            ->where('used', false)
            ->firstOrFail();
    
        // Xử lý avatar (nếu có)
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
    
        // Chuẩn bị dữ liệu lưu
        $data = [
            'name' => $request->name,
            'original_email' => $invite->email,
            'ngay_sinh' => $request->dob,
            'dia_chi' => $request->address,
            'so_dien_thoai' => $request->phone,
            'avatar' => $avatarPath,
        ];
    
        // Gán chi_nhanh_id hoặc rap_phim_id tùy theo loại quản lý
        if ($invite->loai_quan_ly == 1) {
            $data['chi_nhanh_id'] = $invite->chi_nhanh_id;
        } elseif ($invite->loai_quan_ly == 2) {
            $data['rap_phim_id'] = $invite->rap_phim_id;
        }
    
        AdminRequest::create($data);
    
        $invite->used = true;
        $invite->save();
    
        return view('auth.invite.thanks');
    }
    

}
