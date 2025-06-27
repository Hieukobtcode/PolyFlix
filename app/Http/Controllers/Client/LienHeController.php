<?php

namespace App\Http\Controllers\Client;

use App\Models\LienHe;
use App\Models\CauHinh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LienHeController extends Controller
{
    /**
     * Hiển thị trang liên hệ
     */
    public function index()
    {
        // Lấy thông tin cấu hình website để hiển thị thông tin liên hệ
        $cauHinh = CauHinh::first();

        return view('client.lien-he.index', compact('cauHinh'));
    }

    /**
     * Xử lý gửi form liên hệ
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'noi_dung' => 'required|string|min:10',
        ], [
            'ten.required' => 'Vui lòng nhập họ tên',
            'ten.max' => 'Họ tên không được vượt quá 255 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'noi_dung.required' => 'Vui lòng nhập nội dung',
            'noi_dung.min' => 'Nội dung phải có ít nhất 10 ký tự',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Tạo liên hệ mới
            LienHe::create([
                'ten' => $request->ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'noi_dung' => $request->noi_dung,
                'nguon_goc' => 'Website',
                'phan_loai' => 'Liên hệ từ khách hàng',
                'trang_thai' => false, // Chưa xử lý
                'muc_do_uu_tien' => 'binh_thuong',
                'da_phan_hoi' => false,
            ]);

            return redirect()->back()->with([
                'success' => 'Cảm ơn bạn đã liên hệ với PolyFlix!',
                'success_detail' => 'Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong vòng 24 giờ. Vui lòng kiểm tra email để nhận thông tin phản hồi.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi gửi liên hệ. Vui lòng thử lại sau.')
                ->withInput();
        }
    }
}
