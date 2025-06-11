<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DinhDangPhim;
use Illuminate\Http\Request;

class DinhDangPhimController extends Controller
{
    public function index()
    {
        $dinhDangPhims = DinhDangPhim::orderBy('create_at', 'desc')->paginate(10);
        return view('admin.dinh-dang-phim.index', compact('dinhDangPhims'));
    }

    public function create()
    {
        return view('admin.dinh-dang-phim.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_dinh_dang' => 'required|string|max:255|unique:dinh_dang_phims',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoạt động,không hoạt động',
        ]);

        DinhDangPhim::create($request->all());

        return redirect()->route('admin.dinh-dang-phim.index')
            ->with('success', 'Định dạng phim đã được tạo thành công!');
    }

    public function show($id)
    {
        $dinhDangPhim = DinhDangPhim::findOrFail($id);
        return view('admin.dinh-dang-phim.show', compact('dinhDangPhim'));
    }

    public function edit($id)
    {
        $dinhDangPhim = DinhDangPhim::findOrFail($id);
        return view('admin.dinh-dang-phim.edit', compact('dinhDangPhim'));
    }

    public function update(Request $request, $id)
    {
        $dinhDangPhim = DinhDangPhim::findOrFail($id);

        $request->validate([
            'ten_dinh_dang' => 'required|string|max:255|unique:dinh_dang_phims,ten_dinh_dang,' . $id,
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoạt động,không hoạt động',
        ]);

        $dinhDangPhim->update($request->all());

        return redirect()->route('admin.dinh-dang-phim.index')
            ->with('success', 'Định dạng phim đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $dinhDangPhim = DinhDangPhim::findOrFail($id);

        if ($dinhDangPhim->phims()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xóa định dạng phim này vì đang được sử dụng!');
        }

        $dinhDangPhim->delete();

        return redirect()->route('admin.dinh-dang-phim.index')
            ->with('success', 'Định dạng phim đã được xóa thành công!');
    }
}