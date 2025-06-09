<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoDoGheRequest;
use App\Models\LoaiGhe;
use App\Models\PhongChieu;
use App\Models\SoDoGhe;
use Illuminate\Http\Request;

class SoDoGheController extends Controller
{

    public function store(SoDoGheRequest $request)
    {
        try {
            $phongchieuId = $request->phong_id;
            $cauTruc = $request->ma_tran_ghe;

            $soDoGhe = SoDoGhe::create([
                'phong_chieu_id' => $phongchieuId,
                'cau_truc_ghe'   => json_encode($cauTruc),
            ]);

            $phong = PhongChieu::find($request->phong_id);
            $phong->so_do_ghe_id = $soDoGhe->id;
            $phong->save();

            return response()->json(['success' => true, 'redirectUrl' => route('admin.so-do-ghe.edit', $soDoGhe->id)]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra! Vui lòng thử lại.'])->withInput();
        }
    }

    public function edit(string $id)
    {

        $loaiGhes = LoaiGhe::all();
        $mauGhes = LoaiGhe::pluck('chu_thich_mau_ghe','id');
        $phongChieu = PhongChieu::where('so_do_ghe_id', $id)->first();
        $soDoGhe = SoDoGhe::findOrFail($id);

        $decodedLevel1 = json_decode($soDoGhe->cau_truc_ghe, true);
        $cauTrucGhe = json_decode($decodedLevel1, true); 

        $soDoGhe->cau_truc_ghe = $cauTrucGhe;

        return view('admin.so-do-ghe.edit', compact('soDoGhe', 'phongChieu','loaiGhes','mauGhes'));
    }
}
