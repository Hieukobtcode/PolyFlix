<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;

class PhanQuyenController extends Controller
{
    // Hiển thị danh sách phân quyền
    public function index()
    {
        $phanQuyens = PhanQuyen::paginate(10);
        return view('admin.phan-quyen.index', compact('phanQuyens'));
    }

    // Hiển thị form tạo phân quyền mới
    public function create()
    {
        return view('admin.phan-quyen.create');
    }

    // Lưu phân quyền mới vào database
    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:phan_quyens,slug',
        ]);

        PhanQuyen::create([
            'ten' => $request->ten,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.phan-quyen.index')->with('success', 'Thêm phân quyền thành công.');
    }

    // Hiển thị chi tiết phân quyền
    public function show($id)
    {
        $phanQuyen = PhanQuyen::findOrFail($id);
        return view('admin.phan-quyen.show', compact('phanQuyen'));
    }

    // Hiển thị form chỉnh sửa phân quyền
    public function edit($id)
    {
        $phanQuyen = PhanQuyen::findOrFail($id);
        return view('admin.phan-quyen.edit', compact('phanQuyen'));
    }

    // Cập nhật phân quyền
    public function update(Request $request, $id)
    {
        $phanQuyen = PhanQuyen::findOrFail($id);

        $request->validate([
            'ten' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:phan_quyens,slug,' . $phanQuyen->id,
        ]);

        $phanQuyen->update([
            'ten' => $request->ten,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.phan-quyen.index')->with('success', 'Cập nhật phân quyền thành công.');
    }

    // Xóa phân quyền
    public function destroy($id)
    {
        $phanQuyen = PhanQuyen::findOrFail($id);
        $phanQuyen->delete();

        return redirect()->route('admin.phan-quyen.index')->with('success', 'Xóa phân quyền thành công.');
    }
}
