<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GheNgoi;
use App\Models\LoaiGhe;
use App\Models\PhongChieu;
use App\Models\RapPhim;
use App\Models\SoDoGhe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GheNgoiController extends Controller
{

    public function store(Request $request)
    {
        $soDoGheId = $request->soDoGheId;
        $soDoGhe = SoDoGhe::findOrFail($soDoGheId);
        $phongChieuId = $request->input('phong_chieu_id');
        $phongChieu = PhongChieu::findOrFail($phongChieuId);
        $rapPhimId = $phongChieu->rap_phim_id;
        $seatDataJson = $request->input('seat_data');
        $seatData = json_decode($seatDataJson, true);
        foreach ($seatData as $seat) {
            GheNgoi::create([
                'phong_chieu_id' => $phongChieuId,
                'loai_ghe' => $seat['loai'],
                'hang' => $seat['row'],
                'cot' => $seat['col'],
                'ma_ghe' => $seat['row'] . $seat['col'],
            ]);
        }

        $soDoGhe->trang_thai = 0;
        $soDoGhe->save();
        $soGhe = GheNgoi::where('phong_chieu_id',$phongChieuId)->count();
        $phongChieu->so_ghe = $soGhe;
        $phongChieu->save();


        return redirect()
            ->route('admin.rap-phim.show', $rapPhimId)
            ->with('success', 'Thêm sơ đồ ghế thành công cho phòng chiếu ' . $phongChieu->ten_phong);
    }

    public function show($id)
    {
        $phongChieu = PhongChieu::findOrfail($id);
        $phongChieuId = $phongChieu->id;
        $soDoGhe = SoDoGhe::where('phong_chieu_id',$phongChieuId)->first();

        if( $soDoGhe->trang_thai == 1 ){
            return redirect()->route('admin.so-do-ghe.edit',$soDoGhe->id);
        }

        $gheGrouped = GheNgoi::where('phong_chieu_id', $id)
            ->orderBy('hang')
            ->orderBy('cot')
            ->get()
            ->groupBy('hang');
        $soGhe = GheNgoi::where('loai_ghe', '!=', 'empty')
            ->where('phong_chieu_id', $id)
            ->count();
        $gheGroupedArray = $gheGrouped->toArray();

        return view('admin.ghe-ngoi.show', compact('gheGroupedArray', 'phongChieu', 'soGhe', 'phongChieuId','soDoGhe'));
    }

    public function edit($id)
    {
        $ghe = GheNgoi::findOrFail($id);
        $phongChieus = PhongChieu::all();
        $loaiGhes = LoaiGhe::all();
        return view('admin.ghe-ngoi.edit', compact('ghe', 'phongChieus', 'loaiGhes'));
    }

    public function updateSeat(Request $request)
    {
        $phongChieuId = $request->phongChieuId;

        $seatsJson = $request->input('seats_json', '[]');

        $seatsId = json_decode($seatsJson, true);

        print_r($seatsId);

        if (!is_array($seatsId)) {
            $seatsId = [];
        }

        $allSeats = GheNgoi::where('phong_chieu_id', $phongChieuId)->get();

        $countSeats  = 0;
        $seatReturn  = 0;

        foreach ($allSeats as $seat) {
            $isSelected = in_array($seat->id, $seatsId);

            if ($isSelected) {
                if ($seat->trang_thai !== 'bao_tri') {
                    $seat->trang_thai = 'bao_tri';
                    $seat->save();
                    $countSeats++;
                }
            } else {
                if ($seat->trang_thai === 'bao_tri') {
                    $seat->trang_thai = 'trong';
                    $seat->save();
                    $seatReturn++;
                }
            }
        }

        return redirect()
            ->route('admin.ghe-ngoi.show', $phongChieuId)
            ->with('success', "Đã cập nhật: $countSeats ghế chuyển sang bảo trì, $seatReturn ghế được phục hồi.");
    }


    public function destroy($id)
    {
        $ghe = GheNgoi::findOrFail($id);
        $ghe->delete();

        return redirect()->route('admin.ghe-ngoi.index')->with('success', 'Xóa ghế thành công');
    }
}
