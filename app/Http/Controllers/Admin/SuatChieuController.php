<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuatChieu;
use App\Models\Phim;
use App\Models\PhongChieu;
use Carbon\Carbon;
use App\Models\ChiNhanh;

class SuatChieuController extends Controller
{
    /**
     * Hiển thị danh sách các suất chiếu.
     */
    // public function index()
    // {
    //     $suatChieus = SuatChieu::with(['phim', 'phongChieu'])->get();
    //     return view('admin.suat-chieu.index', compact('suatChieus'));
    // }

    public function index(Request $request)
    {
        $suatChieus = SuatChieu::with(['phim', 'phongChieu.rapPhim.chiNhanh'])
            ->when($request->filled('chi_nhanh'), function ($q) use ($request) {
                $q->whereHas('phongChieu.rapPhim.chiNhanh', function ($q2) use ($request) {
                    $q2->where('id', $request->chi_nhanh);
                });
            })
            ->when($request->filled('rap'), function ($q) use ($request) {
                $q->whereHas('phongChieu.rapPhim', function ($q2) use ($request) {
                    $q2->where('id', $request->rap);
                });
            })
            ->when($request->filled('ngay_chieu'), function ($q) use ($request) {
                $q->whereDate('ngay_chieu', $request->ngay_chieu);
            })
            ->get();

        // Lấy danh sách chi nhánh kèm các rạp phim thuộc chi nhánh đó
        $chiNhanhs = ChiNhanh::with('rapPhims')->get();

        return view('admin.suat-chieu.index', compact('suatChieus', 'chiNhanhs'));
    }

    /**
     * Hiển thị form tạo mới suất chiếu.
     */
    public function create()
    {
        $id = request()->phimId;
        $phims = Phim::findOrFail($id);
        $rapPhims = $phims->rapPhims; // Lấy danh sách rạp liên kết với phim
        $rapPhimIds = $rapPhims->pluck('id'); // Lấy mảng id rạp

        $phongChieus = PhongChieu::whereIn('rap_phim_id', $rapPhimIds)->get();

        return view('admin.suat-chieu.create', compact('phims', 'phongChieus'));
    }

    public function store(Request $request)
    {
        $cheDo = $request->input('che_do');

        // Validate chung
        $validated = $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'phien_ban_phim' => 'required|in:long_tieng,phu_de',
            'ngay_chieu' => 'required|date',
            'trang_thai' => 'nullable|in:hoat_dong,tam_dung',
            'che_do' => 'required|in:thu_cong,tu_dong',
        ]);

        // ----------------------------------------------------------
        // THỦ CÔNG
        // ----------------------------------------------------------
        if ($cheDo === 'thu_cong') {
            $request->validate([
                'thucong_bat_dau' => 'required|array|min:1',
                'thucong_ket_thuc' => 'required|array|min:1',
                'thucong_bat_dau.*' => 'required|date_format:H:i',
                'thucong_ket_thuc.*' => 'required|date_format:H:i|after:thucong_bat_dau.*',
            ]);

            foreach ($request->thucong_bat_dau as $index => $gioBatDau) {
                $gioKetThuc = $request->thucong_ket_thuc[$index];

                $daTonTai = SuatChieu::where('phong_chieu_id', $validated['phong_chieu_id'])
                    ->where('ngay_chieu', $validated['ngay_chieu'])
                    ->where(function ($query) use ($gioBatDau, $gioKetThuc) {
                        $query->whereBetween('bat_dau', [$gioBatDau, $gioKetThuc])
                            ->orWhereBetween('ket_thuc', [$gioBatDau, $gioKetThuc])
                            ->orWhere(function ($q) use ($gioBatDau, $gioKetThuc) {
                                $q->where('bat_dau', '<=', $gioBatDau)
                                    ->where('ket_thuc', '>=', $gioKetThuc);
                            });
                    })->exists();

                if ($daTonTai) {
                    return redirect()->back()->withErrors([
                        "Suất chiếu từ $gioBatDau đến $gioKetThuc bị trùng với suất khác."
                    ]);
                }

                SuatChieu::create([
                    'phim_id' => $validated['phim_id'],
                    'phong_chieu_id' => $validated['phong_chieu_id'],
                    'phien_ban_phim' => $validated['phien_ban_phim'],
                    'ngay_chieu' => $validated['ngay_chieu'],
                    'bat_dau' => $gioBatDau,
                    'ket_thuc' => $gioKetThuc,
                    'trang_thai' => $validated['trang_thai'] ?? 'tam_dung',
                ]);
            }
        }

        // ----------------------------------------------------------
        // TỰ ĐỘNG
        // ----------------------------------------------------------
        elseif ($cheDo === 'tu_dong') {
            $request->validate([
                'tudong_bat_dau' => 'required|date_format:H:i',
                'tudong_ket_thuc' => 'required|date_format:H:i|after:tudong_bat_dau',
            ]);

            $phim = Phim::findOrFail($validated['phim_id']);
            $thoiLuong = $phim->thoi_luong;

            $gioBatDau = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_bat_dau);
            $gioKetThucChung = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_ket_thuc);

            while (true) {
                $gioKetThucSuat = $gioBatDau->copy()->addMinutes($thoiLuong);

                if ($gioKetThucSuat->gt($gioKetThucChung)) break;

                $batDauStr = $gioBatDau->format('H:i');
                $ketThucStr = $gioKetThucSuat->format('H:i');

                $daTonTai = SuatChieu::where('phong_chieu_id', $validated['phong_chieu_id'])
                    ->where('ngay_chieu', $validated['ngay_chieu'])
                    ->where(function ($query) use ($batDauStr, $ketThucStr) {
                        $query->whereBetween('bat_dau', [$batDauStr, $ketThucStr])
                            ->orWhereBetween('ket_thuc', [$batDauStr, $ketThucStr])
                            ->orWhere(function ($q) use ($batDauStr, $ketThucStr) {
                                $q->where('bat_dau', '<=', $batDauStr)
                                    ->where('ket_thuc', '>=', $ketThucStr);
                            });
                    })->exists();

                if (!$daTonTai) {
                    SuatChieu::create([
                        'phim_id' => $validated['phim_id'],
                        'phong_chieu_id' => $validated['phong_chieu_id'],
                        'phien_ban_phim' => $validated['phien_ban_phim'],
                        'ngay_chieu' => $validated['ngay_chieu'],
                        'bat_dau' => $batDauStr,
                        'ket_thuc' => $ketThucStr,
                        'trang_thai' => $validated['trang_thai'] ?? 'tam_dung',
                    ]);
                }

                $gioBatDau = $gioKetThucSuat->addMinutes(20); // nghỉ giữa các suất
            }
        }

        return redirect()->route('admin.suat-chieu.index')->with('success', 'Tạo suất chiếu thành công.');
    }


    /**
     * Hiển thị chi tiết một suất chiếu.
     */
    public function show($id)
    {
        // $suatChieu = SuatChieu::with(['phim', 'phongChieu'])->findOrFail($id);
        $suatChieu = SuatChieu::with([
            'phim.chiNhanhs',
            'phim.rapPhims',
            'phongChieu.rapPhim.chiNhanh'
        ])->findOrFail($id);

        return view('admin.suat-chieu.show', compact('suatChieu'));
    }

    /**
     * Hiển thị form chỉnh sửa suất chiếu.
     */
    public function edit($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);

        // Lấy phim hiện tại từ suất chiếu
        $phim = Phim::findOrFail($suatChieu->phim_id);

        // Lấy danh sách rạp liên kết với phim này
        $rapPhims = $phim->rapPhims;
        $rapPhimIds = $rapPhims->pluck('id');

        // Lấy phòng chiếu thuộc các rạp này
        $phongChieus = PhongChieu::whereIn('rap_phim_id', $rapPhimIds)->get();

        // Danh sách tất cả phim để hiển thị trong form dropdown
        $phims = Phim::all();

        return view('admin.suat-chieu.edit', compact('suatChieu', 'phims', 'phongChieus'));
    }
    /**
     * Cập nhật suất chiếu.
     */
    public function update(Request $request, $id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        $cheDo = $request->input('che_do');

        $validated = $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'phien_ban_phim' => 'required|in:long_tieng,phu_de',
            'ngay_chieu' => 'required|date',
            'trang_thai' => 'nullable|in:hoat_dong,tam_dung',
            'che_do' => 'required|in:thu_cong,tu_dong',
        ]);

        // ------------------------------
        // THỦ CÔNG
        // ------------------------------
        if ($cheDo === 'thu_cong') {
            $request->validate([
                'thucong_bat_dau' => 'required|date_format:H:i',
                'thucong_ket_thuc' => 'required|date_format:H:i|after:thucong_bat_dau',
            ]);

            $gioBatDau = $request->thucong_bat_dau;
            $gioKetThuc = $request->thucong_ket_thuc;

            $daTonTai = SuatChieu::where('id', '!=', $suatChieu->id)
                ->where('phong_chieu_id', $validated['phong_chieu_id'])
                ->where('ngay_chieu', $validated['ngay_chieu'])
                ->where(function ($query) use ($gioBatDau, $gioKetThuc) {
                    $query->whereBetween('bat_dau', [$gioBatDau, $gioKetThuc])
                        ->orWhereBetween('ket_thuc', [$gioBatDau, $gioKetThuc])
                        ->orWhere(function ($q) use ($gioBatDau, $gioKetThuc) {
                            $q->where('bat_dau', '<=', $gioBatDau)
                                ->where('ket_thuc', '>=', $gioKetThuc);
                        });
                })->exists();

            if ($daTonTai) {
                return redirect()->back()->withErrors([
                    "Suất chiếu từ $gioBatDau đến $gioKetThuc bị trùng với suất khác."
                ]);
            }

            $suatChieu->update([
                'phim_id' => $validated['phim_id'],
                'phong_chieu_id' => $validated['phong_chieu_id'],
                'phien_ban_phim' => $validated['phien_ban_phim'],
                'ngay_chieu' => $validated['ngay_chieu'],
                'bat_dau' => $gioBatDau,
                'ket_thuc' => $gioKetThuc,
                'trang_thai' => $validated['trang_thai'] ?? 'tam_dung',
            ]);
        }

        // ------------------------------
        // TỰ ĐỘNG
        // ------------------------------
        elseif ($cheDo === 'tu_dong') {
            $request->validate([
                'tudong_bat_dau' => 'required|date_format:H:i',
                'tudong_ket_thuc' => 'required|date_format:H:i|after:tudong_bat_dau',
            ]);

            $phim = Phim::findOrFail($validated['phim_id']);
            $thoiLuong = $phim->thoi_luong;

            $gioBatDau = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_bat_dau);
            $gioKetThuc = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_ket_thuc);
            $gioKetThucSuat = $gioBatDau->copy()->addMinutes($thoiLuong);

            if ($gioKetThucSuat->gt($gioKetThuc)) {
                return redirect()->back()->withErrors(['tudong_ket_thuc' => 'Khung giờ không đủ để chiếu phim.']);
            }

            $batDauStr = $gioBatDau->format('H:i');
            $ketThucStr = $gioKetThucSuat->format('H:i');

            $daTonTai = SuatChieu::where('id', '!=', $suatChieu->id)
                ->where('phong_chieu_id', $validated['phong_chieu_id'])
                ->where('ngay_chieu', $validated['ngay_chieu'])
                ->where(function ($query) use ($batDauStr, $ketThucStr) {
                    $query->whereBetween('bat_dau', [$batDauStr, $ketThucStr])
                        ->orWhereBetween('ket_thuc', [$batDauStr, $ketThucStr])
                        ->orWhere(function ($q) use ($batDauStr, $ketThucStr) {
                            $q->where('bat_dau', '<=', $batDauStr)
                                ->where('ket_thuc', '>=', $ketThucStr);
                        });
                })->exists();

            if ($daTonTai) {
                return redirect()->back()->withErrors([
                    "Suất chiếu từ $batDauStr đến $ketThucStr bị trùng với suất khác."
                ]);
            }

            $suatChieu->update([
                'phim_id' => $validated['phim_id'],
                'phong_chieu_id' => $validated['phong_chieu_id'],
                'phien_ban_phim' => $validated['phien_ban_phim'],
                'ngay_chieu' => $validated['ngay_chieu'],
                'bat_dau' => $batDauStr,
                'ket_thuc' => $ketThucStr,
                'trang_thai' => $validated['trang_thai'] ?? 'tam_dung',
            ]);
        }

        return redirect()->route('admin.suat-chieu.index')->with('success', 'Cập nhật suất chiếu thành công.');
    }


    /**
     * Xóa một suất chiếu.
     */
    public function destroy($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        $suatChieu->delete();
        return redirect()->route('admin.suat-chieu.index')->with('success', 'Đã xóa suất chiếu.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $suat = SuatChieu::findOrFail($id);
        $trangThai = $request->input('trang_thai');

        if (!in_array($trangThai, ['hoat_dong', 'tam_dung'])) {
            return response()->json(['success' => false], 400);
        }

        $suat->trang_thai = $trangThai;
        $suat->save();

        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        SuatChieu::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true]);
    }

    public function bulkToggleStatus(Request $request)
    {
        $suatChieus = SuatChieu::whereIn('id', $request->ids)->get();

        foreach ($suatChieus as $suat) {
            $suat->trang_thai = $suat->trang_thai === 'hoat_dong' ? 'tam_dung' : 'hoat_dong';
            $suat->save();
        }

        return response()->json(['success' => true]);
    }
}