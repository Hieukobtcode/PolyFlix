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
    // hàm riêng check giờ có nằm trong khoảng 07:00–02:00 hôm sau
    private function isTrongKhoangChoPhep(Carbon $ngayChieu, Carbon $gioBatDau, Carbon $gioKetThuc): bool
    {
        $gioChoPhepBatDau = $ngayChieu->copy()->setTime(7, 0);
        $gioChoPhepKetThuc = $ngayChieu->copy()->addDay()->setTime(2, 0);
        return $gioBatDau->gte($gioChoPhepBatDau) && $gioKetThuc->lte($gioChoPhepKetThuc);
    }

    // Thêm hàm helper để làm tròn thời gian
    private function lamTronThoiGian(Carbon $time): Carbon
    {
        $minutes = $time->minute;
        $roundedMinutes = ceil($minutes / 5) * 5;

        $result = $time->copy();

        if ($roundedMinutes == 60) {
            $result->addHour()->startOfHour();
        } else {
            $result->minute($roundedMinutes);
        }

        return $result;
    }

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
            }, function ($q) {
                $q->whereDate('ngay_chieu', '>=', Carbon::today());
            })

            ->when($request->filled('ten_phim'), function ($q) use ($request) {
                $q->whereHas('phim', function ($q2) use ($request) {
                    $q2->where('ten_phim', 'like', '%' . $request->ten_phim . '%');
                });
            })
            ->orderBy('ngay_chieu', 'asc')
            ->get();

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
        // $phongChieus = PhongChieu::whereIn('rap_phim_id', $rapPhimIds)->get();
        // Thêm điều kiện cho phòng chiếu
        $phongChieus = PhongChieu::whereIn('rap_phim_id', $rapPhimIds)
            ->where('status', 'hoat_dong')
            ->whereNotNull('so_ghe')
            ->get();
        $dinhDangs = $phim->dinhDangs;
        $phuDes = $phim->phuDes;

        return view('admin.suat-chieu.create', compact('phim', 'phongChieus', 'dinhDangs', 'phuDes'));
    }

    public function store(Request $request)
    {
        $cheDo = $request->input('che_do');
        $phim = Phim::findOrFail($request['phim_id']);
        $thoiGianNghi = 20; // thời gian nghỉ giữa các suất chiếu

        $validated = $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'ngay_chieu' => 'required|date',
            'trang_thai' => 'nullable|in:hoat_dong,tam_dung',
            'che_do' => 'required|in:thu_cong,tu_dong',
            'phien_ban_phim' => [
                'required',
                function ($attr, $value, $fail) use ($phim) {
                    $allowed = [];
                    foreach ($phim->dinhDangs as $f) {
                        foreach ($phim->phuDes as $s) {
                            $allowed[] = strtolower(Str::slug($f->ten_dinh_dang) . '-' . Str::slug($s->ten_phu_de));
                        }
                    }
                    if (!in_array($value, $allowed)) {
                        $fail('Phiên bản phim không hợp lệ.');
                    }
                }
            ],
        ]);

        $ngayChieu = Carbon::parse($validated['ngay_chieu']);

        if ($ngayChieu->lt(Carbon::today())) {
            return redirect()->back()
                ->withErrors(['ngay_chieu' => 'Ngày chiếu không được nhỏ hơn ngày hiện tại.'])
                ->withInput();
        }

        // $ngayPhatHanh = Carbon::parse($phim->ngay_phat_hanh)->startOfDay();
        $ngayKetThuc = Carbon::parse($phim->ngay_ket_thuc)->endOfDay();

        if ($ngayChieu->gt($ngayKetThuc)) {
            return redirect()->back()
                ->withErrors(['ngay_chieu' => 'Ngày chiếu không được sau ngày kết thúc của phim.'])
                ->withInput();
        }

        // ——— THỦ CÔNG ———
        if ($cheDo === 'thu_cong') {
            $request->validate([
                'thucong_bat_dau' => 'required|array|min:1',
                // 'thucong_ket_thuc' không còn cần thiết nếu chỉ dựa vào thời lượng phim
                'thucong_bat_dau.*' => 'required|date_format:H:i',
            ]);

            $thoiLuong = $phim->thoi_luong;
            $cacSuatChieuDeXuat = [];

            // BƯỚC 1: Thu thập và tính toán tất cả các suất chiếu được đề xuất
            foreach ($request->thucong_bat_dau as $i => $bdStr) {
                $bd = Carbon::parse($validated['ngay_chieu'] . ' ' . $bdStr);
                // Làm tròn thời gian bắt đầu
                $bd = $this->lamTronThoiGian($bd);
                $kt = $bd->copy()->addMinutes($thoiLuong);

                $cacSuatChieuDeXuat[] = [
                    'bd' => $bd,
                    'kt' => $kt,
                ];
            }

            // BƯỚC 2: Sắp xếp các suất chiếu đề xuất theo thời gian bắt đầu
            usort($cacSuatChieuDeXuat, fn($a, $b) => $a['bd'] <=> $b['bd']);

            // BƯỚC 3: Kiểm tra chéo các suất chiếu với nhau và với DB
            for ($i = 0; $i < count($cacSuatChieuDeXuat); $i++) {
                $suatHienTai = $cacSuatChieuDeXuat[$i];
                $bdHienTai = $suatHienTai['bd'];
                $ktHienTai = $suatHienTai['kt'];

                // 3.1: Kiểm tra với suất chiếu ngay trước nó trong danh sách (để đảm bảo có thời gian nghỉ)
                if ($i > 0) {
                    $suatTruoc = $cacSuatChieuDeXuat[$i - 1];
                    $ktTruoc = $suatTruoc['kt'];

                    // Tính khoảng cách thực tế giữa 2 suất
                    $thoiGianNghiThucTe = $bdHienTai->diffInMinutes($ktTruoc);

                    if ($thoiGianNghiThucTe < $thoiGianNghi) {
                        return redirect()->back()->withErrors([
                            "Không đủ thời gian nghỉ giữa suất chiếu kết thúc lúc {$ktTruoc->format('H:i')} và suất chiếu bắt đầu lúc {$bdHienTai->format('H:i')} (yêu cầu tối thiểu {$thoiGianNghi} phút)."
                        ])->withInput();
                    }
                }

                // 3.2: Kiểm tra với các suất chiếu đã có trong Database
                $bdStr = $bdHienTai->format('H:i');
                $ktStr = $ktHienTai->format('H:i');
                $ngayThucTe = $bdHienTai->format('Y-m-d');

                $daTonTai = SuatChieu::where('phong_chieu_id', $validated['phong_chieu_id'])
                    ->where('ngay_chieu', $ngayThucTe)
                    ->where(fn($q) => $q->where('bat_dau', '<', $ktStr)->where('ket_thuc', '>', $bdStr))
                    ->exists();

                if ($daTonTai) {
                    return redirect()->back()->withErrors([
                        "Suất chiếu từ {$bdStr} đến {$ktStr} bị trùng với một suất chiếu đã có."
                    ])->withInput();
                }
            }

            // BƯỚC 4: Nếu tất cả kiểm tra đều qua, tiến hành tạo tất cả suất chiếu
            foreach ($cacSuatChieuDeXuat as $suat) {
                SuatChieu::create([
                    'phim_id' => $validated['phim_id'],
                    'phong_chieu_id' => $validated['phong_chieu_id'],
                    'phien_ban_phim' => $validated['phien_ban_phim'],
                    'ngay_chieu' => $suat['bd']->format('Y-m-d'),
                    'bat_dau' => $suat['bd']->format('H:i'),
                    'ket_thuc' => $suat['kt']->format('H:i'),
                    'trang_thai' => $validated['trang_thai'] ?? 'hoat_dong',
                ]);
            }
        }

        // ——— TỰ ĐỘNG ———
        elseif ($cheDo === 'tu_dong') {
            $request->validate([
                'tudong_bat_dau' => 'required|date_format:H:i',
                'tudong_ket_thuc' => 'required|date_format:H:i',
            ]);

            $bd = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_bat_dau);
            // Làm tròn thời gian bắt đầu ban đầu
            $bd = $this->lamTronThoiGian($bd);

            $kt = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_ket_thuc);
            if ($kt->lt($bd)) $kt->addDay();

            if (!$this->isTrongKhoangChoPhep($ngayChieu, $bd, $kt)) {
                return redirect()->back()->withErrors([
                    'tudong_bat_dau' => 'Giờ bắt đầu và kết thúc phải nằm trong khung 07:00–02:00 hôm sau.'
                ])->withInput();
            }

            $thoiLuong = $phim->thoi_luong;

            while (true) {
                $ktSuat = $bd->copy()->addMinutes($thoiLuong);
                if ($ktSuat->gt($kt)) break;

                $bdStr = $bd->format('H:i');
                $ktStr = $ktSuat->format('H:i');
                $ngayThucTe = $bd->format('Y-m-d');

                $trung = SuatChieu::where('phong_chieu_id', $validated['phong_chieu_id'])
                    ->where('ngay_chieu', $ngayThucTe) // ✅ check theo ngày thực
                    ->where(function ($q) use ($bdStr, $ktStr) {
                        $q->whereBetween('bat_dau', [$bdStr, $ktStr])
                            ->orWhereBetween('ket_thuc', [$bdStr, $ktStr])
                            ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $bdStr)->where('ket_thuc', '>=', $ktStr));
                    })->exists();

                if (!$trung) {
                    SuatChieu::create([
                        'phim_id' => $validated['phim_id'],
                        'phong_chieu_id' => $validated['phong_chieu_id'],
                        'phien_ban_phim' => $validated['phien_ban_phim'],
                        'ngay_chieu' => $ngayThucTe, // ✅ ngày bắt đầu thực
                        'bat_dau' => $bdStr,
                        'ket_thuc' => $ktStr,
                        'trang_thai' => $validated['trang_thai'] ?? 'hoat_dong',
                    ]);
                }

                $bd = $ktSuat->addMinutes($thoiGianNghi);
                $bd = $this->lamTronThoiGian($bd);
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
            'ngay_chieu' => 'required|date',
            'trang_thai' => 'nullable|in:hoat_dong,tam_dung',
            'che_do' => 'required|in:thu_cong,tu_dong',
            'phien_ban_phim' => [
                'required',
                function ($attr, $value, $fail) use ($phim) {
                    $allowed = [];
                    foreach ($phim->dinhDangs as $f) {
                        foreach ($phim->phuDes as $s) {
                            $allowed[] = strtolower(Str::slug($f->ten_dinh_dang) . '-' . Str::slug($s->ten_phu_de));
                        }
                    }
                    if (!in_array($value, $allowed)) {
                        $fail('Phiên bản phim không hợp lệ.');
                    }
                }
            ],
        ]);

        $ngayChieu = Carbon::parse($validated['ngay_chieu']);

        if ($ngayChieu->lt(Carbon::today())) {
            return redirect()->back()
                ->withErrors(['ngay_chieu' => 'Ngày chiếu không được nhỏ hơn ngày hiện tại.'])
                ->withInput();
        }

        // $ngayPhatHanh = Carbon::parse($phim->ngay_phat_hanh)->startOfDay();
        $ngayKetThuc = Carbon::parse($phim->ngay_ket_thuc)->endOfDay();

        if ($ngayChieu->gt($ngayKetThuc)) {
            return redirect()->back()
                ->withErrors(['ngay_chieu' => 'Ngày chiếu không được sau ngày kết thúc của phim.'])
                ->withInput();
        }

        // ——— THỦ CÔNG ———
        if ($cheDo === 'thu_cong') {
            $request->validate([
                'thucong_bat_dau' => 'required|date_format:H:i',
                'thucong_ket_thuc' => 'required|date_format:H:i',
            ]);

            $bd = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->thucong_bat_dau);
            $kt = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->thucong_ket_thuc);
            if ($kt->lt($bd)) $kt->addDay();

            if (!$this->isTrongKhoangChoPhep($ngayChieu, $bd, $kt)) {
                return redirect()->back()->withErrors([
                    'thucong_bat_dau' => 'Thời gian suất chiếu phải nằm trong khung 07:00–02:00 hôm sau.'
                ])->withInput();
            }

            $bdStr = $bd->format('H:i');
            $ktStr = $kt->format('H:i');
            $ngayThucTe = $bd->format('Y-m-d');

            $trung = SuatChieu::where('id', '!=', $suatChieu->id)
                ->where('phong_chieu_id', $validated['phong_chieu_id'])
                ->where('ngay_chieu', $ngayThucTe)
                ->where(function ($q) use ($bdStr, $ktStr) {
                    $q->whereBetween('bat_dau', [$bdStr, $ktStr])
                        ->orWhereBetween('ket_thuc', [$bdStr, $ktStr])
                        ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $bdStr)->where('ket_thuc', '>=', $ktStr));
                })->exists();

            if ($trung) {
                return redirect()->back()->withErrors([
                    "Suất chiếu từ $bdStr đến $ktStr bị trùng."
                ])->withInput();
            }

            $suatChieu->update([
                'phim_id' => $validated['phim_id'],
                'phong_chieu_id' => $validated['phong_chieu_id'],
                'phien_ban_phim' => $validated['phien_ban_phim'],
                'ngay_chieu' => $ngayThucTe, // ✅ ngày bắt đầu thực tế
                'bat_dau' => $bdStr,
                'ket_thuc' => $ktStr,
                'trang_thai' => $validated['trang_thai'] ?? 'hoat_dong',
            ]);
        }

        // ——— TỰ ĐỘNG ———
        elseif ($cheDo === 'tu_dong') {
            $request->validate([
                'tudong_bat_dau' => 'required|date_format:H:i',
                'tudong_ket_thuc' => 'required|date_format:H:i',
            ]);

            $bd = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_bat_dau);
            $kt = Carbon::parse($validated['ngay_chieu'] . ' ' . $request->tudong_ket_thuc);
            if ($kt->lt($bd)) $kt->addDay();

            if (!$this->isTrongKhoangChoPhep($ngayChieu, $bd, $kt)) {
                return redirect()->back()->withErrors([
                    'tudong_bat_dau' => 'Thời gian suất chiếu phải nằm trong khung 07:00–02:00 hôm sau.'
                ])->withInput();
            }

            $ktSuat = $bd->copy()->addMinutes($phim->thoi_luong);
            if ($ktSuat->gt($kt)) {
                return redirect()->back()->withErrors([
                    'tudong_ket_thuc' => 'Khung giờ không đủ để chiếu phim.'
                ])->withInput();
            }

            $bdStr = $bd->format('H:i');
            $ktStr = $ktSuat->format('H:i');
            $ngayThucTe = $bd->format('Y-m-d');

            $trung = SuatChieu::where('id', '!=', $suatChieu->id)
                ->where('phong_chieu_id', $validated['phong_chieu_id'])
                ->where('ngay_chieu', $ngayThucTe)
                ->where(function ($q) use ($bdStr, $ktStr) {
                    $q->whereBetween('bat_dau', [$bdStr, $ktStr])
                        ->orWhereBetween('ket_thuc', [$bdStr, $ktStr])
                        ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $bdStr)->where('ket_thuc', '>=', $ktStr));
                })->exists();

            if ($trung) {
                return redirect()->back()->withErrors([
                    "Suất chiếu từ $bdStr đến $ktStr bị trùng."
                ])->withInput();
            }

            $suatChieu->update([
                'phim_id' => $validated['phim_id'],
                'phong_chieu_id' => $validated['phong_chieu_id'],
                'phien_ban_phim' => $validated['phien_ban_phim'],
                'ngay_chieu' => $ngayThucTe, // ✅ ngày bắt đầu thực tế
                'bat_dau' => $bdStr,
                'ket_thuc' => $ktStr,
                'trang_thai' => $validated['trang_thai'] ?? 'hoat_dong',
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

    public function theoPhongVaNgay(Request $req)
    {
        $req->validate([
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'ngay_chieu'     => 'required|date',
        ]);

        $suatChieus = SuatChieu::with('PhongChieu')
            ->where('phong_chieu_id', $req->phong_chieu_id)
            ->where('ngay_chieu', $req->ngay_chieu)
            ->orderBy('bat_dau')
            ->get();

        $data = $suatChieus->map(function ($s) {
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