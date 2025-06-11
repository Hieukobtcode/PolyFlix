<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LoaiPhong;
use Illuminate\Http\Request;

class LoaiPhongController extends Controller
{
    // Hiển thị danh sách loại phòng
    public function index()
    {
        $loaiPhongs = LoaiPhong::paginate(10); // 10 là số loại phòng mỗi trang
        return view('admin.loai-phong.index', compact('loaiPhongs'));
    }
    

    // Hiển thị form tạo mới loại phòng
    public function create()
    {
        return view('admin.loai-phong.create');
    }

    // Lưu loại phòng mới vào database
    public function store(Request $request)
    {
        $request->validate([
            'ten_loai_phong' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
        ]);

        LoaiPhong::create([
            'ten_loai_phong' => $request->ten_loai_phong,
            'mo_ta' => $request->mo_ta,
            'create_at' => now(),
            'update_at' => now(),
        ]);

        return redirect()->route('admin.loai-phong.index')->with('success', 'Thêm loại phòng thành công.');
    }

    // Hiển thị chi tiết loại phòng
    public function show($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        return view('admin.loai-phong.show', compact('loaiPhong'));
    }

    // Hiển thị form chỉnh sửa loại phòng
    public function edit($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        return view('admin.loai-phong.edit', compact('loaiPhong'));
    }

    // Cập nhật loại phòng trong database
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_loai_phong' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
        ]);

        $loaiPhong = LoaiPhong::findOrFail($id);
        $loaiPhong->update([
            'ten_loai_phong' => $request->ten_loai_phong,
            'mo_ta' => $request->mo_ta,
            'update_at' => now(),
        ]);

        return redirect()->route('admin.loai-phong.index')->with('success', 'Cập nhật loại phòng thành công.');
    }

    // Xóa loại phòng
    public function destroy($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        $loaiPhong->delete();

        return redirect()->route('admin.loai-phong.index')->with('success', 'Xóa loại phòng thành công.');
    }
}
