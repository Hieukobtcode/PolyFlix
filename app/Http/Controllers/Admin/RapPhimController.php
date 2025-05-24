<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RapPhim;
use App\Models\ChiNhanh;

class RapphimController extends Controller
{
    public function index(Request $request)
    {
        $query = RapPhim::query();

        if ($request->has('keyword') && $request->keyword) {
            $query->where('ten_rap', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('status') && $request->status) {
            $query->where('trang_thai', $request->status);
        }

        $rapPhims = $query->paginate(10);

        return view('admin.rap-phim.index', compact('rapPhims'));
    }

    public function create()
    {
        $chiNhanhs = ChiNhanh::all();
        return view('admin.rap-phim.create', compact('chiNhanhs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
            'ten_rap' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'so_dien_thoai' => 'required|string',
            'email' => 'required|email|unique:rap_phims,email',
            'trang_thai' => 'required|in:đang hoạt động,bảo trì,đã đóng',
        ]);

        RapPhim::create($request->all());

        return redirect()->route('admin.rap-phim.index')->with('success', 'Thêm rạp chiếu thành công');
    }

    public function edit($id)
    {
        $rapPhim = RapPhim::findOrFail($id);
        $chiNhanhs = ChiNhanh::all();

        return view('admin.rap-phim.edit', compact('rapPhim', 'chiNhanhs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
            'ten_rap' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'so_dien_thoai' => 'required|string',
            'email' => 'required|email|unique:rap_phims,email,' . $id,
            'trang_thai' => 'required|in:đang hoạt động,bảo trì,đã đóng',
        ]);

        $rapPhim = RapPhim::findOrFail($id);
        $rapPhim->update($request->all());

        return redirect()->route('admin.rap-phim.index')->with('success', 'Cập nhật rạp chiếu thành công');
    }

    public function destroy($id)
    {
        RapPhim::findOrFail($id)->delete();

        return redirect()->route('admin.rap-phim.index')->with('success', 'Xóa rạp chiếu thành công');
    }

    public function trash()
    {
        $rapPhims = RapPhim::onlyTrashed()->paginate(10);
        return view('admin.rap-phim.trash', compact('rapPhims'));
    }

    public function restore($id)
    {
        $rapPhim = RapPhim::onlyTrashed()->findOrFail($id);
        $rapPhim->restore();
        return redirect()->route('admin.rap-phim.trash')->with('success', 'Khôi phục rạp chiếu thành công');
    }

    public function forceDelete($id)
    {
        $rapPhim = RapPhim::onlyTrashed()->findOrFail($id);
        $rapPhim->forceDelete();
        return redirect()->route('admin.rap-phim.trash')->with('success', 'Xóa vĩnh viễn rạp chiếu thành công');
    }
}
