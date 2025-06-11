<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CauHinh;

class CauHinhController extends Controller
{
    public function index()
    {
        $cauHinh = CauHinh::first();
        return view('admin.cau-hinh.index', compact('cauHinh'));
    }

    public function update(Request $request)
{
    $cauHinh = CauHinh::first();

    if (!$cauHinh) {
        return redirect()->back()->with('error', 'Cấu hình không tồn tại');
    }

    // ✅ Validate dữ liệu
    $validated = $request->validate([
      
    'ten_website' => 'required|string|max:255',
    'ten_thuong_hieu' => 'required|string|max:255',
    'khau_hieu' => 'required|string|max:255',
    'so_dien_thoai' => 'required|string|max:20',
    'email' => 'required|email|max:255',
    'dia_chi' => 'required|string|max:500',
    'giay_phep_kinh_doanh' => 'required|string|max:255',
    'thoi_gian_lam_viec' => 'required|string|max:100',
    'link_facebook' => 'required|url|max:255',
    'link_youtube' => 'required|url|max:255',
    'ban_quyen' => 'required|string|max:255',

    'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:2048',
    'anh_chinh_sach_bao_mat' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:2048',
    'anh_dieu_khoan_dich_vu' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:2048',
    'anh_gioi_thieu' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:2048',
]);



    $data = $request->only([
        'ten_website',
        'ten_thuong_hieu',
        'khau_hieu',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'giay_phep_kinh_doanh',
        'thoi_gian_lam_viec',
        'link_facebook',
        'link_youtube',
        'ban_quyen',
    ]);

    // ✅ Xử lý upload ảnh nếu có
    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('uploads', 'public');
    }

    if ($request->hasFile('anh_chinh_sach_bao_mat')) {
        $data['anh_chinh_sach_bao_mat'] = $request->file('anh_chinh_sach_bao_mat')->store('uploads', 'public');
    }

    if ($request->hasFile('anh_dieu_khoan_dich_vu')) {
        $data['anh_dieu_khoan_dich_vu'] = $request->file('anh_dieu_khoan_dich_vu')->store('uploads', 'public');
    }

    if ($request->hasFile('anh_gioi_thieu')) {
        $data['anh_gioi_thieu'] = $request->file('anh_gioi_thieu')->store('uploads', 'public');
    }

    $cauHinh->update($data);

    return redirect()->route('admin.cau-hinh.index')->with('success', 'Cập nhật cấu hình thành công!');
}

}