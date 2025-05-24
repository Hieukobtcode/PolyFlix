<?php

namespace App\Http\Controllers\Admin;

use App\Models\VaiTro;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaiTroController extends Controller
{
    public function index()
    {
        $vaiTros = VaiTro::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.vai-tro.index', compact('vaiTros'));
    }

    public function create()
    {
        $phanQuyens = PhanQuyen::all();
        return view('admin.vai-tro.create', compact('phanQuyens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255|unique:vai_tros,ten',
            'mo_ta' => 'nullable|string',
            'phan_quyen_ids' => 'array', // danh sách checkbox
        ]);

        $vaiTro = VaiTro::create($request->only('ten', 'mo_ta'));

        // Gán phân quyền (nếu có)
        if ($request->has('phan_quyen_ids')) {
            $vaiTro->phanQuyens()->sync($request->phan_quyen_ids);
        }

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Vai trò đã được tạo thành công!');
    }

    public function show($id)
    {
        $vaiTro = VaiTro::with('phanQuyens')->findOrFail($id);
        return view('admin.vai-tro.show', compact('vaiTro'));
    }

    public function edit($id)
    {
        $vaiTro = VaiTro::findOrFail($id);
        $phanQuyens = PhanQuyen::all();
        $phanQuyenDaGan = $vaiTro->phanQuyens()->pluck('phan_quyen_id')->toArray();

        return view('admin.vai-tro.edit', compact('vaiTro', 'phanQuyens', 'phanQuyenDaGan'));
    }

    public function update(Request $request, $id)
    {
        $vaiTro = VaiTro::findOrFail($id);

        $request->validate([
            'ten' => 'required|string|max:255|unique:vai_tros,ten,' . $id,
            'mo_ta' => 'nullable|string',
            'phan_quyen_ids' => 'array',
        ]);

        $vaiTro->update($request->only('ten', 'mo_ta'));

        // Cập nhật phân quyền
        $vaiTro->phanQuyens()->sync($request->phan_quyen_ids ?? []);

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Vai trò đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $vaiTro = VaiTro::findOrFail($id);

        // Hủy liên kết các quyền trước khi xóa
        $vaiTro->phanQuyens()->detach();

        $vaiTro->delete();

        return redirect()->route('admin.vai-tro.index')
            ->with('success', 'Vai trò đã được xóa thành công!');
    }
}
