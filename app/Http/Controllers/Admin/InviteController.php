<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\MoiQuanLyRap;
use Illuminate\Support\Str;
use App\Models\AdminRequest;
use App\Models\QuanLyInvite;
use Illuminate\Http\Request;
use App\Mail\MoiQuanLyChiNhanh;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;





class InviteController extends Controller
{

    //
    public function sendInvite(Request $request)
    {
        // dd($request);
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('quan_ly_invites', 'email')->where(function ($query) {
                    return $query->where('used', 0);
                }),
            ],
            'loai_quan_ly' => 'required|in:1,2',
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

    public function cancel(Request $request)
    {
        $request->validate([
            'loai_quan_ly' => 'required|in:1,2',
        ]);

        if ($request->loai_quan_ly == 1) {
            // Hủy lời mời quản lý chi nhánh
            $request->validate([
                'chi_nhanh_id' => 'required|integer|exists:chi_nhanhs,id',
            ]);

            DB::table('quan_ly_invites')
                ->where('chi_nhanh_id', $request->chi_nhanh_id)
                ->where('loai_quan_ly', 1)
                ->where('used', 0)
                ->update(['used' => 1]);
        } elseif ($request->loai_quan_ly == 2) {
            // Hủy lời mời quản lý rạp
            $request->validate([
                'rap_phim_id' => 'required|integer|exists:rap_phims,id',
            ]);

            DB::table('quan_ly_invites')
                ->where('rap_phim_id', $request->rap_phim_id)
                ->where('loai_quan_ly', 2)
                ->where('used', 0)
                ->update(['used' => 1]);
        }

        return back()->with('success', 'Đã hủy lời mời quản lý.');
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
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone' => 'required|string|max:20',
        ], [
            'token.required' => 'Thiếu mã xác thực lời mời.',
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên không hợp lệ.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'dob.required' => 'Vui lòng chọn ngày sinh.',
            'dob.date' => 'Ngày sinh không đúng định dạng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.string' => 'Số điện thoại không hợp lệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        ]);
        $invite = QuanLyInvite::where('token', $request->token)
            ->where('expires_at', '>', now())
            ->where('used', false)
            ->firstOrFail();

        // Chuẩn bị dữ liệu lưu
        $data = [
            'name' => $request->name,
            'original_email' => $invite->email,
            'ngay_sinh' => $request->dob,
            'so_dien_thoai' => $request->phone,
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
