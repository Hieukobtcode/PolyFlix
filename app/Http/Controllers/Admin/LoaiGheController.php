<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiGhe;
use Illuminate\Http\Request;

class LoaiGheController extends Controller
{
    public function index()
    {
        $loaiGhes = LoaiGhe::paginate(10);
        return view('admin.loai-ghe.index', compact('loaiGhes'));
    }

    public function create()
    {
        return view('admin.loai-ghe.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_loai_ghe' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'phu_thu' => 'nullable|numeric|min:0',
        ]);

        LoaiGhe::create($request->all());

        return redirect()->route('admin.loai-ghe.index')->with('success', 'Thêm loại ghế thành công');
    }

    public function show(string $id)
    {
        $loaiGhe = LoaiGhe::findOrFail($id);
        return response()->json($loaiGhe); 
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ten_loai_ghe' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'phu_thu' => 'nullable|numeric|min:0',
        ]);

        $loaiGhe = LoaiGhe::findOrFail($id);
        $loaiGhe->update($request->all());

        return redirect()->route('admin.loai-ghe.index')->with('success', 'Cập nhật loại ghế thành công!');
    }

    public function destroy(string $id)
    {
        $loaiGhe = LoaiGhe::findOrFail($id);
        $loaiGhe->delete();

        return redirect()->route('admin.loai-ghe.index')->with('success', 'Xóa loại ghế thành công!');
    }
}
