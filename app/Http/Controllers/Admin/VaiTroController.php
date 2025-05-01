<?php

namespace App\Http\Controllers\Admin;

use App\Models\VaiTro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaiTroController extends Controller
{
    // Hiển thị danh sách vai trò
    public function index()
    {
        $vaiTros = VaiTro::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.vai-tro.index', compact('vaiTros'));
    }

    // Hiển thị form tạo vai trò mới
    public function create()
    {
        return view('admin.vai-tro.create');
    }

    // Lưu vai trò mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255|unique:vai_tros,ten',
            'mo_ta' => 'nullable|string',
        ]);

        VaiTro::create($request->all());

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Vai trò đã được tạo thành công!');
    }

    // Hiển thị chi tiết vai trò
    public function show($id)
    {
        $vaiTro = VaiTro::findOrFail($id);
        return view('admin.vai-tro.show', compact('vaiTro'));
    }

    // Hiển thị form chỉnh sửa vai trò
    public function edit($id)
    {
        $vaiTro = VaiTro::findOrFail($id);
        return view('admin.vai-tro.edit', compact('vaiTro'));
    }

    // Cập nhật vai trò trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $vaiTro = VaiTro::findOrFail($id);

        $request->validate([
            'ten' => 'required|string|max:255|unique:vai_tros,ten,' . $id,
            'mo_ta' => 'nullable|string',
        ]);

        $vaiTro->update($request->all());

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Vai trò đã được cập nhật thành công!');
    }

    // Xóa vai trò khỏi cơ sở dữ liệu
    public function destroy($id)
    {
        $vaiTro = VaiTro::findOrFail($id);

        // Kiểm tra nếu vai trò này đang được sử dụng, không thể xóa
        // if ($vaiTro->users()->count() > 0) {
        //     return redirect()->back()
        //         ->with('error', 'Không thể xóa vai trò này vì đang được sử dụng!');
        // }

        $vaiTro->delete();

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Vai trò đã được xóa thành công!');
    }
}
