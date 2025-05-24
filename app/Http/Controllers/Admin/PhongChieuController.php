<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiPhong;
use App\Models\PhongChieu;
use App\Models\RapPhim;
use Illuminate\Http\Request;

class PhongChieuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phongChieus = PhongChieu::with('rapPhim', 'loaiPhong')->paginate(10);
        return view('admin.phong-chieu.index', compact('phongChieus'));
    }

    public function create()
    {
        $rapPhims = RapPhim::all();
        $loaiPhongs = LoaiPhong::all();
        return view('admin.phong-chieu.create', compact('rapPhims', 'loaiPhongs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_phong' => 'required|string|max:255',
            'rap_phim_id' => 'required|exists:rap_phims,id',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'status' => 'required|in:sẵn sàng,không khả dụng,bảo trì',
        ]);

        PhongChieu::create($request->all());

        return redirect()->route('admin.phong-chieu.index')->with('success', 'Thêm phòng chiếu thành công');
    }

    public function show(string $id)
    {

        $phongChieu = PhongChieu::with('rapPhim', 'loaiPhong')->findOrFail($id);
        return view('admin.phong-chieu.show', compact('phongChieu'));
    }

    public function edit(string $id)
    {
        $phongChieu = PhongChieu::findOrFail($id);
        $rapPhims = RapPhim::all();
        $loaiPhongs = LoaiPhong::all();

        return view('admin.phong-chieu.edit', compact('phongChieu', 'rapPhims', 'loaiPhongs'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ten_phong' => 'required|string|max:255',
            'rap_phim_id' => 'required|exists:rap_phims,id',
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'status' => 'required|in:sẵn sàng,không khả dụng,bảo trì',
        ]);

        $phongChieu = PhongChieu::findOrFail($id);
        $phongChieu->update($request->all());

        return redirect()->route('phong-chieus.index')->with('success', 'Cập nhật phòng chiếu thành công!');
    }

    public function destroy(string $id)
    {
        $phongChieu = PhongChieu::findOrFail($id);
        $phongChieu->delete();

        return redirect()->route('phong-chieus.index')->with('success', 'Xóa phòng chiếu thành công!');
    }
}
