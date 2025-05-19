<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhMucDoAn;

class DanhMucDoAnController extends Controller
{
    public function index()
    {
        $danhMucs = DanhMucDoAn::paginate(10);
        return view('admin.danh-muc-do-an.index', compact('danhMucs'));
    }

    public function create()
    {
        return view('admin.danh-muc-do-an.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255|unique:danh_muc_do_ans,ten',
        ]);

        DanhMucDoAn::create(['ten' => $request->ten]);

        return redirect()->route('admin.danh-muc-do-an.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit(DanhMucDoAn $danhMucDoAn)
    {
        return view('admin.danh-muc-do-an.edit', compact('danhMucDoAn'));
    }

    public function update(Request $request, DanhMucDoAn $danhMucDoAn)
    {
        $request->validate([
            'ten' => 'required|string|max:255|unique:danh_muc_do_ans,ten,' . $danhMucDoAn->id,
        ]);

        $danhMucDoAn->update(['ten' => $request->ten]);

        return redirect()->route('admin.danh-muc-do-an.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(DanhMucDoAn $danhMucDoAn)
    {
        $danhMucDoAn->delete();
        return redirect()->route('admin.danh-muc-do-an.index')->with('success', 'Đã xóa danh mục!');
    }
}
