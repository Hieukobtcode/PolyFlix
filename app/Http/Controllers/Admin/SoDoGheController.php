<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoDoGheRequest;
use App\Models\PhongChieu;
use App\Models\SoDoGhe;
use Illuminate\Http\Request;

class SoDoGheController extends Controller
{

    public function store(SoDoGheRequest $request)
    {
        try {
            $phongchieuId = $request->phong_id;
            $soHang = $request->so_hang;
            $soCot = $request->so_cot;

            $thuong = $request->ghe_thuong;
            $vip = $request->ghe_vip;
            $doi = $request->ghe_doi;

            $hangGhe = $thuong + $vip + $doi;
            $soGhe = ($thuong + $vip + $doi) * $soHang;

            $cauTruc = [];

            for ($i = 0; $i < $hangGhe; $i++) {
                $rowLabel = chr(65 + $i);
                if ($i < $thuong) {
                    $loai = 'thuong';
                } elseif ($i < $thuong + $vip) {
                    $loai = 'vip';
                } else {
                    $loai = 'doi';
                }

                for ($j = 1; $j <= $soCot; $j++) {
                    $cauTruc[$rowLabel . $j] = $loai;
                }
            }

            $soDoGhe = SoDoGhe::create([
                'phong_chieu_id' => $phongchieuId,
                'cau_truc_ghe'   => json_encode($cauTruc),
                'so_hang_thuong' => $thuong,
                'so_hang_vip'    => $vip,
                'so_hang_doi'    => $doi,
                'so_hang'        => $soHang,
                'so_cot'         => $soCot,
                'mo_ta'          => $request->mo_ta,
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
        $phongChieu = PhongChieu::where('so_do_ghe_id', $id)->first();
        $soDoGhe = SoDoGhe::findOrFail($id);

        $soDoGhe->cau_truc_ghe = is_array($soDoGhe->cau_truc_ghe) ? $soDoGhe->cau_truc_ghe : json_decode($soDoGhe->cau_truc_ghe, true);

        return view('admin.so-do-ghe.edit',compact('soDoGhe','phongChieu'));
    }

}
