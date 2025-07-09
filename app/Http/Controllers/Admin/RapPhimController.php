<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoaiGhe;
use App\Models\RapPhim;
use App\Models\ChiNhanh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RapPhimController extends Controller
{
        public function index(Request $request)
    {
        $query = RapPhim::query();

        // ✅ Quản lý chi nhánh chỉ thấy rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2) {
            $query->whereHas('chiNhanh', function ($q) {
                $q->where('quan_ly_id', Auth::id());
            });
        }

        // ✅ Quản lý rạp chỉ thấy rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3) {
            $query->where('quan_ly_id', Auth::id());
        }

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
        $id = request()->chiNhanhId;
        $chiNhanh = ChiNhanh::findOrFail($id);

        //  Quản lý chi nhánh chỉ tạo rạp trong chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.index')
                ->with('error', 'Bạn không có quyền tạo rạp cho chi nhánh này.');
        }

        return view('admin.rap-phim.create', compact('chiNhanh'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_rap' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'trang_thai' => 'required|in:đang hoạt động,bảo trì,đã đóng',
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
        ]);

        $chiNhanh = ChiNhanh::findOrFail($validated['chi_nhanh_id']);

        // Quản lý chi nhánh chỉ lưu rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.index')
                ->with('error', 'Bạn không có quyền thêm rạp cho chi nhánh này.');
        }

        RapPhim::create($validated);

        return redirect()
            ->route('admin.chi-nhanh.show', ['chi_nhanh' => $validated['chi_nhanh_id']])
            ->with('success', 'Thêm rạp chiếu thành công');
    }

    public function show($id)
    {
        $loaiGhes = LoaiGhe::all();
        $rapPhim = RapPhim::with(['phongChieus'])->findOrFail($id);

        //  Quản lý chi nhánh chỉ xem rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $rapPhim->chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.index')
                ->with('error', 'Bạn không có quyền xem rạp này.');
        }

        //  Quản lý rạp chỉ xem rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3 && $rapPhim->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.show',$rapPhim->chi_nhanh_id)
                ->with('error', 'Bạn không có quyền xem rạp này.');
        }

        return view('admin.rap-phim.show', compact('rapPhim', 'loaiGhes'));
    }

    public function edit($id)
    {
        $rapPhim = RapPhim::findOrFail($id);
        $chiNhanhs = ChiNhanh::all();

        //  Quản lý chi nhánh chỉ sửa rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $rapPhim->chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa rạp này.');
        }

        //  Quản lý rạp chỉ sửa rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3 && $rapPhim->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa rạp này.');
        }

        return view('admin.rap-phim.edit', compact('rapPhim', 'chiNhanhs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
            'ten_rap' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'trang_thai' => 'required|in:đang hoạt động,bảo trì,đã đóng',
        ]);

        $rapPhim = RapPhim::findOrFail($id);

        //  Quản lý chi nhánh chỉ cập nhật rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $rapPhim->chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền cập nhật rạp này.');
        }

        //  Quản lý rạp chỉ cập nhật rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3 && $rapPhim->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền cập nhật rạp này.');
        }

        $rapPhim->update($request->all());

        return redirect()->route('admin.chi-nhanh.show', $rapPhim->chi_nhanh_id)
            ->with('success', 'Cập nhật rạp chiếu thành công');
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