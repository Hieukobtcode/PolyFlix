<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoDoGheRequest;
use App\Models\SoDoGhe;
use Illuminate\Http\Request;

class SoDoGheController extends Controller
{
    public function index()
    {
        $soDoGhes = SoDoGhe::latest()->paginate(10);
        return view('admin.so-do-ghe.index', compact('soDoGhes'));
    }

    public function store(SoDoGheRequest $request)
    {
        try {
            // Lấy thông tin từ request
            $soHang = $request->so_hang;
            $soCot = $request->so_cot;

            $thuong = $request->so_hang_thuong;
            $vip = $request->so_hang_vip;
            $doi = $request->so_hang_doi;

            $hangGhe = $thuong + $vip + $doi;

            // Tạo cấu trúc ghế
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

            // Lưu vào DB
            $soDoGhe = SoDoGhe::create([
                'ten_so_do'      => $request->ten_so_do,
                'cau_truc_ghe'   => json_encode($cauTruc),
                'so_hang_thuong' => $thuong,
                'so_hang_vip'    => $vip,
                'so_hang_doi'    => $doi,
                'so_hang'        => $soHang,
                'so_cot'         => $soCot,
                'mo_ta'          => $request->mo_ta,
                'trang_thai'     => $request->trang_thai ?? 1,
            ]);

            // Chuyển sang trang edit
            return redirect()->route('admin.so-do-ghe.edit', $soDoGhe->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_create_modal', true); // chỉ mở modal khi có lỗi
        }
    }



    public function show($id)
    {
        $soDoGhe = SoDoGhe::findOrFail($id);

        // Đảm bảo mảng ghế luôn đúng kiểu
        $soDoGhe->cau_truc_ghe = is_array($soDoGhe->cau_truc_ghe)
            ? $soDoGhe->cau_truc_ghe
            : json_decode($soDoGhe->cau_truc_ghe, true);

        return view('admin.so-do-ghe.show', compact('soDoGhe'));
    }

    public function edit($id)
    {
        $soDoGhe = SoDoGhe::findOrFail($id);

        $soDoGhe->cau_truc_ghe = is_array($soDoGhe->cau_truc_ghe)
            ? $soDoGhe->cau_truc_ghe
            : json_decode($soDoGhe->cau_truc_ghe, true);

        return view('admin.so-do-ghe.edit', compact('soDoGhe'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_so_do' => 'required|string|max:255',
            'so_hang'   => 'required|integer|min:1|max:50',
            'so_cot'    => 'required|integer|min:1|max:50',
        ]);

        $soDoGhe = SoDoGhe::findOrFail($id);
        $soDoGhe->update([
            'ten_so_do' => $request->ten_so_do,
            'so_hang'   => $request->so_hang,
            'so_cot'    => $request->so_cot,
            // Nếu cần cập nhật lại cấu trúc ghế => xử lý thêm tại đây
        ]);

        return redirect()->route('admin.so-do-ghe.index')
            ->with('success', 'Cập nhật sơ đồ thành công.');
    }

    public function destroy($id)
    {
        $soDoGhe = SoDoGhe::findOrFail($id);
        $soDoGhe->delete();

        return redirect()->route('admin.so-do-ghe.index')
            ->with('success', 'Xoá sơ đồ thành công.');
    }
}
