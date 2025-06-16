<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\RapPhim;
use App\Models\ChiNhanh;
use Illuminate\Support\Str;
use App\Models\AdminRequest;
use Illuminate\Http\Request;
use App\Mail\AccountApprovedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class RequestController extends Controller
{
    //
    public function index(Request $request)
    {

        $query = AdminRequest::with(['chiNhanh', 'rapPhim']); // Eager load chi nhánh

        if ($request->has('keyword') && $request->keyword) {
            $query->where('original_email', 'like', '%' . $request->keyword . '%')
                ->orWhereHas('chiNhanh', function ($q) use ($request) {
                    $q->where('ten_chi_nhanh', 'like', '%' . $request->keyword . '%');
                });
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('approved', $request->status);
        }

        $requests = $query->latest()->paginate(10);
        // dd($requests);

        return view('admin.requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $request = AdminRequest::findOrFail($id);

        // Tạo email hệ thống và mật khẩu ngẫu nhiên
        $email = 'admin_' . Str::random(6) . '@polyflix.local';
        $password = Str::random(10);

        // Xác định vai trò dựa trên loại yêu cầu
        $vaiTroId = $request->chi_nhanh_id ? 2 : 3; // 2 = quản lý chi nhánh, 3 = quản lý rạp

        // Tạo tài khoản người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => bcrypt($password),
            'vai_tro_id' => $vaiTroId,
            'hoat_dong' => 1,
            'ngay_sinh' => $request->ngay_sinh,
            'dia_chi' => $request->dia_chi,
            'so_dien_thoai' => $request->so_dien_thoai,
            'avatar' => $request->avatar,
        ]);

        // Gán quản lý vào chi nhánh hoặc rạp phim
        if ($request->chi_nhanh_id) {
            $chiNhanh = ChiNhanh::find($request->chi_nhanh_id);
            if ($chiNhanh) {
                $chiNhanh->quan_ly_id = $user->id;
                $chiNhanh->save();
            }
        } elseif ($request->rap_phim_id) {
            $rap = RapPhim::find($request->rap_phim_id);
            if ($rap) {
                $rap->quan_ly_id = $user->id;
                $rap->save();
            }
        }

        // Cập nhật trạng thái duyệt
        $request->approved = 1;
        $request->save();

        // Gửi mail thông báo tài khoản
        Mail::to($request->original_email)->queue(new AccountApprovedMail($email, $password));

        return redirect()->back()->with('success', 'Đã phê duyệt và tạo tài khoản thành công.');
    }

    
    public function reject($id)
    {
        $request = AdminRequest::findOrFail($id);
        $request->approved = 2; // 2 = từ chối
        $request->save();

        return redirect()->back()->with('success', 'Đã từ chối yêu cầu.');
    }
}
