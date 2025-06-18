<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuatChieu;
use App\Models\Phim;
use App\Models\PhongChieu;
use Carbon\Carbon;
use App\Models\ChiNhanh;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
        $phim = Phim::findOrFail($id);
        $rapPhims = $phim->rapPhims; // rạp liên kết
        $rapPhimIds = $rapPhims->pluck('id');
        $phongChieus = PhongChieu::whereIn('rap_phim_id', $rapPhimIds)->get();
        $dinhDangs = $phim->dinhDangs;
        $phuDes = $phim->phuDes;

        return view('admin.suat-chieu.create', compact('phim', 'phongChieus', 'dinhDangs', 'phuDes'));
    }

    public function store(Request $request)
    {
        $cheDo = $request->input('che_do');
        $phim = Phim::findOrFail($request['phim_id']);

        $validated = $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            // 'phien_ban_phim' => 'required',
            'ngay_chieu' => 'required|date',
            'trang_thai' => 'nullable|in:hoat_dong,tam_dung',
            'che_do' => 'required|in:thu_cong,tu_dong',
            'phien_ban_phim' => [
                'required',
                function ($attr, $value, $fail) use ($phim) {
                    $allowed = [];
                    foreach ($phim->dinhDangs as $f) {
                        foreach ($phim->phuDes as $s) {
                            $fSlug = Str::slug($f->ten_dinh_dang, '-');
                            $sSlug = Str::slug($s->ten_phu_de, '-');
                            $allowed[] = strtolower($fSlug . '-' . $sSlug);
                        }
                    }
                    if (!in_array($value, $allowed)) {
                        $fail('Phiên bản phim không hợp lệ.');
                    }
                }
            ],
        ]);


        // 🔒 Validate ngày_chieu không vượt ngày_ket_thuc của phim
        if (Carbon::parse($validated['ngay_chieu'])->gt(Carbon::parse($phim->ngay_ket_thuc))) {
            return redirect()->back()
                ->withErrors([
                    'ngay_chieu' => 'Không thể tạo suất chiếu sau ngày kết thúc phim (' . Carbon::parse($phim->ngay_ket_thuc)->format('Y-m-d') . ').'
                ])
                ->withInput();
        }

        // ——— THỦ CÔNG ———
        if ($cheDo === 'thu_cong') {
            $request->validate([
                'thucong_bat_dau' => 'required|array|min:1',
                'thucong_ket_thuc' => 'required|array|min:1',
                'thucong_bat_dau.*' => 'required|date_format:H:i',
                'thucong_ket_thuc.*' => 'required|date_format:H:i|after:thucong_bat_dau.*',
            ]);

            foreach ($request->thucong_bat_dau as $index => $gioBatDau) {
                $gioKetThuc = $request->thucong_ket_thuc[$index];

                if (Carbon::parse($validated['ngay_chieu'] . ' ' . $gioKetThuc)
                    ->gt(Carbon::parse($phim->ngay_ket_thuc)->endOfDay())
                ) {
                    return redirect()->back()
                        ->withErrors([
                            "Suất chiếu kết thúc ($gioKetThuc) vượt quá ngày kết thúc phim ({$phim->ngay_ket_thuc})."
                        ])
                        ->withInput();
                }

                $daTonTai = SuatChieu::where('phong_chieu_id', $validated['phong_chieu_id'])
                    ->where('ngay_chieu', $validated['ngay_chieu'])
                    ->where(function ($q) use ($gioBatDau, $gioKetThuc) {
                        $q->whereBetween('bat_dau', [$gioBatDau, $gioKetThuc])
                            ->orWhereBetween('ket_thuc', [$gioBatDau, $gioKetThuc])
                            ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $gioBatDau)
                                ->where('ket_thuc', '>=', $gioKetThuc));
                    })->exists();

                if ($daTonTai) {
                    return redirect()->back()->withErrors([
                        "Suất chiếu từ $gioBatDau đến $gioKetThuc bị trùng với suất khác."
                    ])->withInput();
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

        // ——— TỰ ĐỘNG ———
        elseif ($cheDo === 'tu_dong') {
            $request->validate([
                'tudong_bat_dau' => 'required|date_format:H:i',
                'tudong_ket_thuc' => 'required|date_format:H:i|after:tudong_bat_dau',
            ]);

            $thoiLuong = $phim->thoi_luong;
            $gioBatDau = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_bat_dau);
            $gioKetChucChung = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_ket_thuc);
            $ketThucPhimNgay = Carbon::parse($phim->ngay_ket_thuc)->endOfDay();

            while (true) {
                if ($gioBatDau->gt($ketThucPhimNgay)) break;

                $gioKetThucSuat = $gioBatDau->copy()->addMinutes($thoiLuong);
                if ($gioKetThucSuat->gt($gioKetChucChung)) break;

                $batDauStr = $gioBatDau->format('H:i');
                $ketThucStr = $gioKetThucSuat->format('H:i');

                $daTonTai = SuatChieu::where('phong_chieu_id', $validated['phong_chieu_id'])
                    ->where('ngay_chieu', $validated['ngay_chieu'])
                    ->where(function ($q) use ($batDauStr, $ketThucStr) {
                        $q->whereBetween('bat_dau', [$batDauStr, $ketThucStr])
                            ->orWhereBetween('ket_thuc', [$batDauStr, $ketThucStr])
                            ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $batDauStr)
                                ->where('ket_thuc', '>=', $ketThucStr));
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

                $gioBatDau = $gioKetThucSuat->addMinutes(20);
            }
        }

        return redirect()->route('admin.suat-chieu.index')
            ->with('success', 'Tạo suất chiếu thành công.');
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
        $phim = Phim::findOrFail($suatChieu->phim_id);
        $rapPhimIds = $phim->rapPhims->pluck('id');
        $phongChieus = PhongChieu::whereIn('rap_phim_id', $rapPhimIds)->get();
        $phims = Phim::all();
        $dinhDangs = $phim->dinhDangs;
        $phuDes = $phim->phuDes;

        return view('admin.suat-chieu.edit', compact('suatChieu', 'phims', 'phongChieus', 'dinhDangs', 'phuDes'));
    }

    public function update(Request $request, $id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        $cheDo = $request->input('che_do');
        $phim = Phim::findOrFail($request['phim_id']);

        $validated = $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            // 'phien_ban_phim' => 'required',
            'ngay_chieu' => 'required|date',
            'trang_thai' => 'nullable|in:hoat_dong,tam_dung',
            'che_do' => 'required|in:thu_cong,tu_dong',
            'phien_ban_phim' => [
                'required',
                function ($attr, $value, $fail) use ($phim) {
                    $allowed = [];
                    foreach ($phim->dinhDangs as $f) {
                        foreach ($phim->phuDes as $s) {
                            $fSlug = Str::slug($f->ten_dinh_dang, '-');
                            $sSlug = Str::slug($s->ten_phu_de, '-');
                            $allowed[] = strtolower($fSlug . '-' . $sSlug);
                        }
                    }
                    if (!in_array($value, $allowed)) {
                        $fail('Phiên bản phim không hợp lệ.');
                    }
                }
            ],
        ]);



        // 🔒 Kiểm tra: ngày_chieu không vượt quá ngày_ket_thuc
        if (Carbon::parse($validated['ngay_chieu'])->gt(Carbon::parse($phim->ngay_ket_thuc))) {
            return redirect()->back()
                ->withErrors([
                    'ngay_chieu' => 'Ngày chiếu không thể vượt quá ngày kết thúc phim (' . Carbon::parse($phim->ngay_ket_thuc)->format('Y-m-d') . ')'
                ])
                ->withInput();
        }

        // THỦ CÔNG
        if ($cheDo === 'thu_cong') {
            $request->validate([
                'thucong_bat_dau' => 'required|date_format:H:i',
                'thucong_ket_thuc' => 'required|date_format:H:i|after:thucong_bat_dau',
            ]);

            $gioBatDau = $request->thucong_bat_dau;
            $gioKetThuc = $request->thucong_ket_thuc;

            // Kiểm tra kết thúc không vượt ngày kết thúc phim
            if (Carbon::parse($validated['ngay_chieu'] . ' ' . $gioKetThuc)
                ->gt(Carbon::parse($phim->ngay_ket_thuc)->endOfDay())
            ) {
                return redirect()->back()
                    ->withErrors([
                        'thucong_ket_thuc' => 'Giờ kết thúc (' . $gioKetThuc . ') vượt quá ngày kết thúc phim.'
                    ])
                    ->withInput();
            }

            $daTonTai = SuatChieu::where('id', '!=', $suatChieu->id)
                ->where('phong_chieu_id', $validated['phong_chieu_id'])
                ->where('ngay_chieu', $validated['ngay_chieu'])
                ->where(function ($q) use ($gioBatDau, $gioKetThuc) {
                    $q->whereBetween('bat_dau', [$gioBatDau, $gioKetThuc])
                        ->orWhereBetween('ket_thuc', [$gioBatDau, $gioKetThuc])
                        ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $gioBatDau)
                            ->where('ket_thuc', '>=', $gioKetThuc));
                })->exists();

            if ($daTonTai) {
                return redirect()->back()
                    ->withErrors([
                        "Suất chiếu từ $gioBatDau đến $gioKetThuc bị trùng."
                    ])->withInput();
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

        // TỰ ĐỘNG
        elseif ($cheDo === 'tu_dong') {
            $request->validate([
                'tudong_bat_dau' => 'required|date_format:H:i',
                'tudong_ket_thuc' => 'required|date_format:H:i|after:tudong_bat_dau',
            ]);

            $thoiLuong = $phim->thoi_luong;
            $gioBatDau = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_bat_dau);
            $gioKetThuc = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_ket_thuc);
            $gioKetThucSuat = $gioBatDau->copy()->addMinutes($thoiLuong);

            // Kiểm tra không đủ thời gian chiếu
            if ($gioKetThucSuat->gt($gioKetThuc)) {
                return redirect()->back()
                    ->withErrors([
                        'tudong_ket_thuc' => 'Khung giờ không đủ để chiếu phim.'
                    ])->withInput();
            }

            $batDauStr = $gioBatDau->format('H:i');
            $ketThucStr = $gioKetThucSuat->format('H:i');

            // Kiểm tra kết thúc không vượt ngày kết thúc phim
            if (Carbon::parse($validated['ngay_chieu'] . ' ' . $ketThucStr)
                ->gt(Carbon::parse($phim->ngay_ket_thuc)->endOfDay())
            ) {
                return redirect()->back()
                    ->withErrors([
                        'tudong_ket_thuc' => 'Giờ kết thúc (' . $ketThucStr . ') vượt ngày kết thúc phim.'
                    ])->withInput();
            }

            $daTonTai = SuatChieu::where('id', '!=', $suatChieu->id)
                ->where('phong_chieu_id', $validated['phong_chieu_id'])
                ->where('ngay_chieu', $validated['ngay_chieu'])
                ->where(function ($q) use ($batDauStr, $ketThucStr) {
                    $q->whereBetween('bat_dau', [$batDauStr, $ketThucStr])
                        ->orWhereBetween('ket_thuc', [$batDauStr, $ketThucStr])
                        ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $batDauStr)
                            ->where('ket_thuc', '>=', $ketThucStr));
                })->exists();

            if ($daTonTai) {
                return redirect()->back()
                    ->withErrors([
                        "Suất chiếu từ $batDauStr đến $ketThucStr bị trùng."
                    ])->withInput();
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

        return redirect()->route('admin.suat-chieu.index')
            ->with('success', 'Cập nhật suất chiếu thành công.');
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

    public function theoPhongVaNgay(Request $req)
    {
        // (1) Validate đầu vào nếu cần
        $req->validate([
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'ngay_chieu'     => 'required|date',
        ]);

        // (2) Lấy danh sách suất chiếu
        $suatChieus = SuatChieu::with('PhongChieu')
            ->where('phong_chieu_id', $req->phong_chieu_id)
            ->where('ngay_chieu', $req->ngay_chieu)
            ->orderBy('bat_dau') // sử dụng đúng tên cột
            ->get();

        // (3) Chuyển dữ liệu sang chuẩn JSON response
        $data = $suatChieus->map(function ($s) {
            // Dùng Carbon để format
            $start = $s->bat_dau ? \Carbon\Carbon::parse($s->bat_dau)->format('H:i') : '00:00';
            $end   = $s->ket_thuc ? \Carbon\Carbon::parse($s->ket_thuc)->format('H:i') : '00:00';

            return [
                'ngay_chieu' => \Carbon\Carbon::parse($s->ngay_chieu)->format('Y-m-d'),
                'gio_bat_dau'  => $start,
                'gio_ket_thuc' => $end,
                'phong'        => optional($s->phongChieu)->ten_phong ?? '(chưa xác định)',
                'phien_ban'    => $s->formatted_version,
            ];
        });

        return response()->json($data);
    }
}