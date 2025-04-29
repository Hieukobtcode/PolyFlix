<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TheLoaiPhim;
use Illuminate\Http\Request;

class TheLoaiPhimController extends Controller
{
    public function index()
    {
        $theLoaiPhims = TheLoaiPhim::orderBy('create_at', 'desc')->paginate(10);
        return view('admin.the-loai-phim.index', compact('theLoaiPhims'));
    }

    public function create()
    {
        return view('admin.the-loai-phim.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_the_loai' => 'required|string|max:255|unique:the_loai_phims',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoạt động,không hoạt động',
        ]);

        TheLoaiPhim::create($request->all());

        return redirect()->route('admin.the-loai-phim.index')
            ->with('success', 'Thể loại phim đã được tạo thành công!');
    }

    public function show($id)
    {
        $theLoaiPhim = TheLoaiPhim::findOrFail($id);
        return view('admin.the-loai-phim.show', compact('theLoaiPhim'));
    }

    public function edit($id)
    {
        $theLoaiPhim = TheLoaiPhim::findOrFail($id);
        return view('admin.the-loai-phim.edit', compact('theLoaiPhim'));
    }

    public function update(Request $request, $id)
    {
        $theLoaiPhim = TheLoaiPhim::findOrFail($id);

        $request->validate([
            'ten_the_loai' => 'required|string|max:255|unique:the_loai_phims,ten_the_loai,' . $id,
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|in:hoạt động,không hoạt động',
        ]);

        $theLoaiPhim->update($request->all());

        return redirect()->route('admin.the-loai-phim.index')
            ->with('success', 'Thể loại phim đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $theLoaiPhim = TheLoaiPhim::findOrFail($id);

        if ($theLoaiPhim->phims()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xóa thể loại phim này vì đang được sử dụng!');
        }

        $theLoaiPhim->delete();

        return redirect()->route('admin.the-loai-phim.index')
            ->with('success', 'Thể loại phim đã được xóa thành công!');
    }
}
