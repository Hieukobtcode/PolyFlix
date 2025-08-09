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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\RapPhim;

class SuatChieuController extends Controller
{
    private function isTrongKhoangChoPhep(Carbon $ngay, Carbon $gioBatDau, Carbon $gioKetThuc): bool
    {
        $gioChoPhepBatDau = $ngay->copy()->setTime(7, 0);
        $gioChoPhepKetThuc = $ngay->copy()->addDay()->setTime(2, 0);
        return $gioBatDau->gte($gioChoPhepBatDau) && $gioKetThuc->lte($gioChoPhepKetThuc);
    }

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
        $user = auth()->user();
        $query = SuatChieu::with(['phim', 'phongChieu.rapPhim.chiNhanh']);

        // Lấy chi nhánh quản lý (nếu có)
        $chiNhanh = $user->chiNhanhDangQuanLy;

        // ===== PHÂN QUYỀN =====
        if ($user->vai_tro_id == 2 && $chiNhanh) {
            // Admin chi nhánh: suất chiếu thuộc các rạp của chi nhánh đó
            $query->whereHas('phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                $q->where('chi_nhanh_id', $chiNhanh->id);
            });
        }

        if ($user->vai_tro_id == 3) {
            // Admin rạp: suất chiếu thuộc rạp của chính mình
            $query->whereHas('phongChieu.rapPhim', function ($q) use ($user) {
                $q->where('id', $user->rap_phim_id);
            });
        }

        // ===== BỘ LỌC =====
        if ($user->vai_tro_id == 1 && $request->filled('chi_nhanh')) {
            $query->whereHas('phongChieu.rapPhim.chiNhanh', function ($q) use ($request) {
                $q->where('id', $request->chi_nhanh);
            });
        }

        if (in_array($user->vai_tro_id, [1, 2]) && $request->filled('rap')) {
            $query->whereHas('phongChieu.rapPhim', function ($q) use ($request) {
                $q->where('id', $request->rap);
            });
        }

        if ($request->filled('ngay_bat_dau')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->ngay_bat_dau);
        } else {
            $query->whereDate('ngay_bat_dau', '>=', Carbon::today());
        }

        if ($request->filled('ngay_ket_thuc')) {
            $query->whereDate('ngay_ket_thuc', '<=', $request->ngay_ket_thuc);
        }

        if ($request->filled('ten_phim')) {
            $query->whereHas('phim', function ($q) use ($request) {
                $q->where('ten_phim', 'like', '%' . $request->ten_phim . '%');
            });
        }

        $suatChieus = $query->orderBy('ngay_bat_dau')
            ->orderBy('bat_dau')
            ->paginate(20);

        // ===== CHI NHÁNH / RẠP HIỂN THỊ TRONG VIEW =====
        if ($user->vai_tro_id == 1) {
            // Admin tổng: lấy tất cả chi nhánh và rạp
            $chiNhanhs = ChiNhanh::with('rapPhims')->get();
        } elseif ($user->vai_tro_id == 2) {
            // Admin chi nhánh: chỉ lấy chi nhánh do họ quản lý
            $chiNhanh = ChiNhanh::where('quan_ly_id', $user->id)->with('rapPhims')->first();
            $chiNhanhs = $chiNhanh ? collect([$chiNhanh]) : collect();
        } elseif ($user->vai_tro_id == 3) {
            // Quản lý rạp: chỉ lấy chi nhánh chứa đúng rạp mà họ quản lý
            $rap = RapPhim::with('chiNhanh')->find($user->rap_phim_id);

            if ($rap && $rap->chiNhanh) {
                $chiNhanh = $rap->chiNhanh;
                // Chỉ nạp lại rạp cụ thể của người dùng
                $chiNhanh->load(['rapPhims' => function ($q) use ($user) {
                    $q->where('id', $user->rap_phim_id);
                }]);
                $chiNhanhs = collect([$chiNhanh]);
            } else {
                $chiNhanhs = collect(); // fallback tránh lỗi null
            }
        } else {
            // Người dùng không hợp lệ hoặc chưa phân quyền đúng
            $chiNhanhs = collect();
        }


        return view('admin.suat-chieu.index', compact('suatChieus', 'chiNhanhs', 'user'));
    }

    public function create()
    {
        $id = request()->phimId;
        $phim = Phim::findOrFail($id);

        // Lấy danh sách rạp chiếu phim này
        $rapPhims = $phim->rapPhims;
        $rapPhimIds = $rapPhims->pluck('id');

        // Lấy danh sách phòng chiếu của các rạp
        $phongChieus = PhongChieu::with('rapPhim.chiNhanh') // eager load các quan hệ
            ->whereIn('rap_phim_id', $rapPhimIds)
            ->where('status', 'hoat_dong')
            ->whereNotNull('so_ghe')
            ->get();

        $dinhDangs = $phim->dinhDangs;
        $phuDes = $phim->phuDes;
        $rapQuanLy = null;
        if (Auth::user()->vai_tro_id == 3) {
            $rapQuanLy = \App\Models\RapPhim::where('quan_ly_id', Auth::id())->first();
        }

        return view('admin.suat-chieu.create', compact(
            'phim',
            'phongChieus',
            'dinhDangs',
            'phuDes',
            'rapQuanLy'
        ));
    }

    private function getDuKienSuatChieu($request, $phim, $validated)
    {
        $cheDo = $request->input('che_do');
        $thoiGianNghi = 20;
        $cacSuatChieuDeXuat = [];

        $ngayBatDau = Carbon::parse($validated['ngay_bat_dau']);
        $ngayKetThuc = Carbon::parse($validated['ngay_ket_thuc']);

        // Tạo một instance tạm thời để lấy tên hiển thị của phiên bản
        $tempSuatChieu = new SuatChieu();
        $tempSuatChieu->phim = $phim;
        $tempSuatChieu->phien_ban_phim = $validated['phien_ban_phim'];
        $formattedPhienBan = $tempSuatChieu->formatted_version;

        if ($cheDo === 'thu_cong') {
            foreach ($request->thucong_bat_dau as $bdStr) {
                $currentDate = $ngayBatDau->copy();
                while ($currentDate->lte($ngayKetThuc)) {
                    $bd = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $bdStr);
                    $bd = $this->lamTronThoiGian($bd);
                    $kt = $bd->copy()->addMinutes($phim->thoi_luong);

                    if ($this->isTrongKhoangChoPhep($currentDate, $bd, $kt)) {
                        $cacSuatChieuDeXuat[] = [
                            'bat_dau' => $bd->format('H:i'),
                            'ket_thuc' => $kt->format('H:i'),
                            'ngay_bat_dau' => $currentDate->format('Y-m-d'),
                            'ngay_ket_thuc' => $currentDate->format('Y-m-d'),
                            'ngay_bat_dau_display' => $currentDate->format('d/m/Y'), // Thêm trường hiển thị
                            'ngay_ket_thuc_display' => $currentDate->format('d/m/Y'), // Thêm trường hiển thị
                            'phong_chieu' => $validated['phong_chieu_id'],
                            'phien_ban' => $validated['phien_ban_phim'],
                            'phien_ban_display' => $formattedPhienBan,
                            'phim_id' => $phim->id
                        ];
                    }

                    $currentDate->addDay();
                }
            }
        } else {
            $gioBatDauMoiNgay = Carbon::parse($request->tudong_bat_dau)->format('H:i');
            $gioKetThucMoiNgay = Carbon::parse($request->tudong_ket_thuc)->format('H:i');

            $currentDate = $ngayBatDau->copy();
            while ($currentDate->lte($ngayKetThuc)) {
                $bd = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $gioBatDauMoiNgay);
                $bd = $this->lamTronThoiGian($bd);

                $ktNgay = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $gioKetThucMoiNgay);
                if ($ktNgay->lt($bd)) {
                    $ktNgay->addDay();
                }

                while (true) {
                    $ktSuat = $bd->copy()->addMinutes($phim->thoi_luong);

                    if ($ktSuat->gt($ktNgay)) {
                        break;
                    }

                    if ($this->isTrongKhoangChoPhep($currentDate, $bd, $ktSuat)) {
                        $trung = false;
                        foreach ($cacSuatChieuDeXuat as $suat) {
                            if ($suat['ngay_bat_dau'] === $currentDate->format('Y-m-d')) {
                                $suatBD = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $suat['bat_dau']);
                                $suatKT = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $suat['ket_thuc']);

                                if (
                                    ($bd->between($suatBD, $suatKT)) ||
                                    ($ktSuat->between($suatBD, $suatKT)) ||
                                    ($bd->lte($suatBD) && $ktSuat->gte($suatKT))
                                ) {
                                    $trung = true;
                                    break;
                                }
                            }
                        }

                        if (!$trung) {
                            $cacSuatChieuDeXuat[] = [
                                'bat_dau' => $bd->format('H:i'),
                                'ket_thuc' => $ktSuat->format('H:i'),
                                'ngay_bat_dau' => $currentDate->format('Y-m-d'),
                                'ngay_ket_thuc' => $currentDate->format('Y-m-d'),
                                'ngay_bat_dau_display' => $currentDate->format('d/m/Y'), // Thêm trường hiển thị
                                'ngay_ket_thuc_display' => $currentDate->format('d/m/Y'), // Thêm trường hiển thị
                                'phong_chieu' => $validated['phong_chieu_id'],
                                'phien_ban' => $validated['phien_ban_phim'],
                                'phien_ban_display' => $formattedPhienBan,
                                'phim_id' => $phim->id
                            ];
                        }
                    }

                    $bd = $ktSuat->copy()->addMinutes($thoiGianNghi);
                    $bd = $this->lamTronThoiGian($bd);
                }

                $currentDate->addDay();
            }
        }

        usort($cacSuatChieuDeXuat, function ($a, $b) {
            $compareDate = strcmp($a['ngay_bat_dau'], $b['ngay_bat_dau']);
            if ($compareDate !== 0) return $compareDate;
            return strcmp($a['bat_dau'], $b['bat_dau']);
        });

        return $cacSuatChieuDeXuat;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $cheDo = $request->input('che_do');
            $phim = Phim::findOrFail($request['phim_id']);

            $validated = $request->validate([
                'phim_id' => 'required|exists:phims,id',
                'phong_chieu_id' => 'required|exists:phong_chieus,id',
                'ngay_bat_dau' => 'required|date',
                'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
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

            if ($cheDo === 'thu_cong') {
                $request->validate([
                    'thucong_bat_dau' => 'required|array|min:1',
                    'thucong_bat_dau.*' => 'required|date_format:H:i',
                ]);
            } else {
                $request->validate([
                    'tudong_bat_dau' => 'required|date_format:H:i',
                    'tudong_ket_thuc' => 'required|date_format:H:i|after:tudong_bat_dau',
                ]);

                $gioBatDau = Carbon::parse($request->tudong_bat_dau);
                $gioKetThuc = Carbon::parse($request->tudong_ket_thuc);

                if ($gioKetThuc->lt($gioBatDau)) {
                    $gioKetThuc->addDay();
                }

                if (!$this->isTrongKhoangChoPhep(Carbon::today(), $gioBatDau, $gioKetThuc)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Khung giờ chiếu phải nằm trong khoảng 07:00–02:00 hôm sau.'
                    ], 422);
                }
            }

            $ngayBatDau = Carbon::parse($validated['ngay_bat_dau']);
            $ngayKetThuc = Carbon::parse($validated['ngay_ket_thuc']);
            $ngayKetThucPhim = Carbon::parse($phim->ngay_ket_thuc)->endOfDay();

            if ($ngayBatDau->lt(Carbon::today())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ngày bắt đầu không được nhỏ hơn ngày hiện tại.'
                ], 422);
            }

            if ($ngayKetThuc->gt($ngayKetThucPhim)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ngày kết thúc không được sau ngày kết thúc của phim.'
                ], 422);
            }

            $cacSuatChieuDeXuat = $this->getDuKienSuatChieu($request, $phim, $validated);

            foreach ($cacSuatChieuDeXuat as $suat) {
                $trung = SuatChieu::where('phong_chieu_id', $suat['phong_chieu'])
                    ->where('ngay_bat_dau', $suat['ngay_bat_dau'])
                    ->where(function ($q) use ($suat) {
                        $q->whereBetween('bat_dau', [$suat['bat_dau'], $suat['ket_thuc']])
                            ->orWhereBetween('ket_thuc', [$suat['bat_dau'], $suat['ket_thuc']])
                            ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $suat['bat_dau'])
                                ->where('ket_thuc', '>=', $suat['ket_thuc']));
                    })->exists();

                if ($trung) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Suất chiếu từ {$suat['bat_dau']} đến {$suat['ket_thuc']} ngày " .
                            Carbon::parse($suat['ngay_bat_dau'])->format('d/m/Y') . " bị trùng.",
                        'du_kien' => $cacSuatChieuDeXuat
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'du_kien' => $cacSuatChieuDeXuat
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo suất chiếu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo suất chiếu.'
            ], 500);
        }
    }

    public function luuSuatChieu(Request $request)
    {
        try {
            DB::beginTransaction();

            $suatChieus = $request->input('suat_chieus');

            foreach ($suatChieus as $suat) {
                SuatChieu::create([
                    'phim_id' => $suat['phim_id'],
                    'phong_chieu_id' => $suat['phong_chieu'],
                    'phien_ban_phim' => $suat['phien_ban'],
                    'ngay_bat_dau' => $suat['ngay_bat_dau'],
                    'ngay_ket_thuc' => $suat['ngay_ket_thuc'],
                    'bat_dau' => $suat['bat_dau'],
                    'ket_thuc' => $suat['ket_thuc'],
                    'trang_thai' => 'hoat_dong'
                ]);

                
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi lưu suất chiếu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu suất chiếu.'
            ], 500);
        }
    }

    public function show($id)
    {
        $suatChieu = SuatChieu::with([
            'phim.chiNhanhs',
            'phim.rapPhims',
            'phongChieu.rapPhim.chiNhanh'
        ])->findOrFail($id);

        return view('admin.suat-chieu.show', compact('suatChieu'));
    }

    public function edit($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);
        $phim = Phim::findOrFail($suatChieu->phim_id);
        $rapPhimIds = $phim->rapPhims->pluck('id');
        $phongChieus = PhongChieu::whereIn('rap_phim_id', $rapPhimIds)
            ->where('status', 'hoat_dong')
            ->whereNotNull('so_ghe')
            ->get();
        $dinhDangs = $phim->dinhDangs;
        $phuDes = $phim->phuDes;

        return view('admin.suat-chieu.edit', compact(
            'suatChieu',
            'phim',
            'phongChieus',
            'dinhDangs',
            'phuDes'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $suatChieu = SuatChieu::findOrFail($id);
            $phim = Phim::findOrFail($request['phim_id']);

            $validated = $request->validate([
                'phim_id' => 'required|exists:phims,id',
                'phong_chieu_id' => 'required|exists:phong_chieus,id',
                'ngay_bat_dau' => 'required|date',
                'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
                'bat_dau' => 'required|date_format:H:i',
                'ket_thuc' => 'required|date_format:H:i|after:bat_dau',
                'trang_thai' => 'nullable|in:hoat_dong,tam_dung',
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

            $ngayBatDau = Carbon::parse($validated['ngay_bat_dau']);
            $ngayKetThuc = Carbon::parse($validated['ngay_ket_thuc']);
            $ngayKetThucPhim = Carbon::parse($phim->ngay_ket_thuc)->endOfDay();

            if ($ngayBatDau->lt(Carbon::today())) {
                return redirect()->back()
                    ->withErrors(['ngay_bat_dau' => 'Ngày bắt đầu không được nhỏ hơn ngày hiện tại.'])
                    ->withInput();
            }

            if ($ngayKetThuc->gt($ngayKetThucPhim)) {
                return redirect()->back()
                    ->withErrors(['ngay_ket_thuc' => 'Ngày kết thúc không được sau ngày kết thúc của phim.'])
                    ->withInput();
            }

            $bd = Carbon::parse($ngayBatDau->format('Y-m-d') . ' ' . $validated['bat_dau']);
            $kt = Carbon::parse($ngayBatDau->format('Y-m-d') . ' ' . $validated['ket_thuc']);

            if (!$this->isTrongKhoangChoPhep($ngayBatDau, $bd, $kt)) {
                return redirect()->back()
                    ->withErrors(['bat_dau' => 'Khung giờ chiếu phải nằm trong khoảng 07:00–02:00 hôm sau.'])
                    ->withInput();
            }

            $trung = SuatChieu::where('id', '!=', $id)
                ->where('phong_chieu_id', $validated['phong_chieu_id'])
                ->where('ngay_bat_dau', $validated['ngay_bat_dau'])
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('bat_dau', [$validated['bat_dau'], $validated['ket_thuc']])
                        ->orWhereBetween('ket_thuc', [$validated['bat_dau'], $validated['ket_thuc']])
                        ->orWhere(fn($qq) => $qq->where('bat_dau', '<=', $validated['bat_dau'])
                            ->where('ket_thuc', '>=', $validated['ket_thuc']));
                })->exists();

            if ($trung) {
                return redirect()->back()
                    ->withErrors(['bat_dau' => 'Suất chiếu này bị trùng với suất chiếu khác.'])
                    ->withInput();
            }

            $suatChieu->update($validated);

            DB::commit();
            return redirect()->route('admin.suat-chieu.index')
                ->with('success', 'Cập nhật suất chiếu thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật suất chiếu: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật suất chiếu.'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $suatChieu = SuatChieu::findOrFail($id);
            $suatChieu->delete();

            DB::commit();
            return redirect()->route('admin.suat-chieu.index')
                ->with('success', 'Đã xóa suất chiếu.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xóa suất chiếu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa suất chiếu.');
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $suat = SuatChieu::findOrFail($id);
            $trangThai = $request->input('trang_thai');

            if (!in_array($trangThai, ['hoat_dong', 'tam_dung'])) {
                return response()->json(['success' => false], 400);
            }

            $suat->trang_thai = $trangThai;
            $suat->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật trạng thái: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            DB::beginTransaction();

            SuatChieu::whereIn('id', $request->ids)->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xóa nhiều suất chiếu: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function bulkToggleStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $suatChieus = SuatChieu::whereIn('id', $request->ids)->get();

            foreach ($suatChieus as $suat) {
                $suat->trang_thai = $suat->trang_thai === 'hoat_dong' ? 'tam_dung' : 'hoat_dong';
                $suat->save();
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật trạng thái nhiều suất: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function theoPhongVaNgay(Request $req)
    {
        try {
            $req->validate([
                'phong_chieu_id' => 'required|exists:phong_chieus,id',
                'ngay_bat_dau' => 'required|date',
            ]);

            $suatChieus = SuatChieu::with(['phongChieu', 'phim'])
                ->where('phong_chieu_id', $req->phong_chieu_id)
                ->whereDate('ngay_bat_dau', '<=', $req->ngay_bat_dau)
                ->whereDate('ngay_ket_thuc', '>=', $req->ngay_bat_dau)
                ->orderBy('bat_dau')
                ->get();

            if ($suatChieus->isEmpty()) {
                return response()->json([
                    'html' => '<tr><td colspan="4" class="text-center">Không có suất chiếu nào.</td></tr>'
                ]);
            }

            $html = '';
            foreach ($suatChieus as $suat) {
                $html .= '<tr>';
                $html .= '<td class="text-center">' . Carbon::parse($req->ngay_bat_dau)->format('d/m/Y') . '</td>';
                $html .= '<td class="text-center">' .
                    Carbon::parse($suat->bat_dau)->format('H:i') . ' - ' .
                    Carbon::parse($suat->ket_thuc)->format('H:i') . '</td>';
                $html .= '<td class="text-center">' . e($suat->phongChieu->ten_phong ?? '(chưa xác định)') . '</td>';
                $html .= '<td class="text-center">' . e($suat->formatted_version ?? 'Không xác định') . '</td>';
                $html .= '</tr>';
            }

            return response()->json(['html' => $html]);
        } catch (ValidationException $e) {
            return response()->json([
                'html' => '<tr><td colspan="4" class="text-center text-danger">Dữ liệu không hợp lệ.</td></tr>'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Lỗi lấy suất chiếu theo phòng và ngày: ' . $e->getMessage());
            return response()->json([
                'html' => '<tr><td colspan="4" class="text-center text-danger">Có lỗi xảy ra khi lấy thông tin suất chiếu.</td></tr>'
            ], 500);
        }
    }
}