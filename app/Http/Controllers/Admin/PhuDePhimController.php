<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhuDePhim;

class PhuDePhimController extends Controller
{
    public function index()
    {
        $phuDePhims = PhuDePhim::orderBy('create_at', 'desc')->paginate(10);
        return view('admin.phu-de-phim.index', compact('phuDePhims'));
    }

    public function create()
    {
        return view('admin.phu-de-phim.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_phu_de' => 'required|string|max:255|unique:phu_de_phims',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoạt động,không hoạt động',
        ]);

        PhuDePhim::create($request->all());

        return redirect()->route('admin.phu-de-phim.index')
            ->with('success', 'Phụ đề phim đã được tạo thành công!');
    }

    public function show($id)
    {
        $phuDePhim = PhuDePhim::findOrFail($id);
        return view('admin.phu-de-phim.show', compact('phuDePhim'));
    }

    public function edit($id)
    {
        $phuDePhim = PhuDePhim::findOrFail($id);
        return view('admin.phu-de-phim.edit', compact('phuDePhim'));
    }

    public function update(Request $request, $id)
    {
        $phuDePhim = PhuDePhim::findOrFail($id);

        $request->validate([
            'ten_phu_de' => 'required|string|max:255|unique:phu_de_phims,ten_phu_de,' . $id,
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoạt động,không hoạt động',
        ]);

        $phuDePhim->update($request->all());

        return redirect()->route('admin.phu-de-phim.index')
            ->with('success', 'Phụ đề phim đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $phuDePhim = PhuDePhim::findOrFail($id);

        if ($phuDePhim->phims()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xóa phụ đề phim này vì đang được sử dụng!');
        }

        $phuDePhim->delete();

        return redirect()->route('admin.phu-de-phim.index')
            ->with('success', 'Phụ đề phim đã được xóa thành công!');
    }
}