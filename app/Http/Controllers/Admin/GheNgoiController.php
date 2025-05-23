<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GheNgoi;
use App\Models\LoaiGhe;
use App\Models\PhongChieu;
use App\Models\SoDoGhe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GheNgoiController extends Controller
{
    public function index()
    {
        $gheNgois = GheNgoi::with('phongChieu', 'loaiGhe')->paginate(20);
        return view('admin.ghe-ngoi.index', compact('gheNgois'));
    }

    public function create()
    {
        $phongChieus = PhongChieu::all();
        $loaiGhes = LoaiGhe::all();
        return view('admin.ghe-ngoi.create', compact('phongChieus', 'loaiGhes'));
    }

    public function createBySoDoGhe($soDoGheId)
    {
        $soDo = SoDoGhe::findOrFail($soDoGheId);
        $loaiGhes = LoaiGhe::all();

        $soHang = $soDo->so_hang;
        $soCot = $soDo->so_cot;

        return view('admin.ghe-ngoi.create-by-so-do', compact('soDo', 'loaiGhes', 'soHang', 'soCot'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'loai_ghe_id' => 'nullable|exists:loai_ghes,id',
            'so_hang' => 'required|string|max:2',
            'so_cot' => 'required|integer|min:1',
            'ma_ghe' => [
                'required',
                'string',
                'max:10',
                Rule::unique('ghe_ngois')->where(function ($query) use ($request) {
                    return $query->where('phong_chieu_id', $request->phong_chieu_id);
                }),
            ],
            'trang_thai' => 'required|in:sẵn sàng,đã giữ,đã đặt,không dùng',
        ]);

        GheNgoi::create($request->all());

        return redirect()->route('admin.ghe-ngoi.index')->with('success', 'Thêm ghế thành công');
    }

    public function show($id)
    {
        $ghe = GheNgoi::with('phongChieu', 'loaiGhe')->findOrFail($id);
        return view('admin.ghe-ngoi.show', compact('ghe'));
    }

    public function edit($id)
    {
        $ghe = GheNgoi::findOrFail($id);
        $phongChieus = PhongChieu::all();
        $loaiGhes = LoaiGhe::all();
        return view('admin.ghe-ngoi.edit', compact('ghe', 'phongChieus', 'loaiGhes'));
    }

    public function update(Request $request, $id)
    {
        $ghe = GheNgoi::findOrFail($id);

        $request->validate([
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'loai_ghe_id' => 'nullable|exists:loai_ghes,id',
            'so_hang' => 'required|string|max:2',
            'so_cot' => 'required|integer|min:1',
            'ma_ghe' => [
                'required',
                'string',
                'max:10',
                Rule::unique('ghe_ngois')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('phong_chieu_id', $request->phong_chieu_id);
                }),
            ],
            'trang_thai' => 'required|in:sẵn sàng,đã giữ,đã đặt,không dùng',
        ]);

        $ghe->update($request->all());

        return redirect()->route('admin.ghe-ngoi.index')->with('success', 'Cập nhật ghế thành công');
    }

    public function destroy($id)
    {
        $ghe = GheNgoi::findOrFail($id);
        $ghe->delete();

        return redirect()->route('admin.ghe-ngoi.index')->with('success', 'Xóa ghế thành công');
    }
}
