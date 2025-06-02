<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiPhong;
use App\Models\PhongChieu;
use App\Models\RapPhim;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $id = request()->rap_phim_id;
        $rapPhim = RapPhim::findOrFail($id);
        $loaiPhongs = LoaiPhong::all();
        return view('admin.phong-chieu.create', compact('rapPhim', 'loaiPhongs'));
    }

    public function store(Request $request)
    {
        $id = $request->rap_phim_id;
        $rapPhim = RapPhim::findOrFail($id);

        $request->validate([
            'ten_phong' => [
                'required',
                'string',
                'max:255',
                Rule::unique('phong_chieus')->where(function ($query) use ($id) {
                    return $query->where('rap_phim_id', $id);
                }),
            ],
            'loai_phong_id' => 'required|exists:loai_phongs,id',
            'status' => 'required|in:hoat_dong,tam_dung,bao_tri',
        ]);

        PhongChieu::create([
            'ten_phong' => $request->ten_phong,
            'rap_phim_id' => $id,
            'loai_phong_id' => $request->loai_phong_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.rap-phim.show', $id)
            ->with('success', 'Thêm phòng chiếu thành công cho rạp');
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
