<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Phim;
use App\Models\RapPhim;
use App\Models\ChiNhanh;
use App\Models\PhuDePhim;
use App\Models\TheLoaiPhim;
use App\Models\DinhDangPhim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhimController extends Controller
{
    protected function capNhatTrangThaiPhim()
    {
        $today = Carbon::today();

        Phim::all()->each(function ($phim) use ($today) {
            $ngayPhatHanh = $phim->ngay_phat_hanh ? Carbon::parse($phim->ngay_phat_hanh) : null;
            $ngayKetThuc = $phim->ngay_ket_thuc ? Carbon::parse($phim->ngay_ket_thuc) : null;

            $trangThaiMoi = $phim->trang_thai;

            if ($ngayPhatHanh && $ngayPhatHanh->isFuture()) {
                $trangThaiMoi = 'sắp chiếu';
            } elseif ($ngayPhatHanh && $ngayKetThuc && $today->between($ngayPhatHanh, $ngayKetThuc)) {
                $trangThaiMoi = 'đang chiếu';
            } elseif ($ngayKetThuc && $today->gt($ngayKetThuc)) {
                $trangThaiMoi = 'đã kết thúc';
            }

            if ($phim->trang_thai !== $trangThaiMoi) {
                $phim->update(['trang_thai' => $trangThaiMoi]);
            }
        });
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->vai_tro_id == 1) {
            // Admin tổng: tất cả phim
            $phims = Phim::orderBy('create_at', 'desc')->paginate(10);
        } elseif ($user->vai_tro_id == 2) {
            // Admin chi nhánh: chỉ phim thuộc chi nhánh họ quản lý
            $phims = Phim::whereHas('chiNhanhs', function ($query) use ($user) {
                $query->where('quan_ly_id', $user->id);
            })->orderBy('create_at', 'desc')->paginate(10);
        } elseif ($user->vai_tro_id == 3) {
            // Admin rạp: chỉ phim thuộc rạp họ quản lý
            $phims = Phim::whereHas('rapPhims', function ($query) use ($user) {
                $query->where('quan_ly_id', $user->id);
            })->orderBy('create_at', 'desc')->paginate(10);
        } else {
            // Vai trò khác: không thấy gì
            $phims = collect();
        }

        return view('admin.phim.index', compact('phims'));
    }


    public function create()
    {
        $theLoaiPhims = TheLoaiPhim::where('trang_thai', 'hoạt động')->get();
        $dinhDangPhims = DinhDangPhim::where('trang_thai', 'hoạt động')->get();
        $phuDePhims = PhuDePhim::where('trang_thai', 'hoạt động')->get();
        $chiNhanhs = ChiNhanh::where('trang_thai', 'hoat_dong')->get();
        $rapPhims = RapPhim::where('trang_thai', 'đang hoạt động')->get();
        return view('admin.phim.create', compact('theLoaiPhims', 'dinhDangPhims', 'phuDePhims', 'chiNhanhs', 'rapPhims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_phim' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'dao_dien' => 'nullable|string|max:255',
            'dien_vien' => 'nullable|string',
            'thoi_luong' => 'nullable|integer|min:1',
            'ngay_phat_hanh' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date',
            'trailer' => 'nullable|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ngon_ngu' => 'nullable|string|max:50',
            'quoc_gia' => 'nullable|string|max:50',
            'do_tuoi' => 'nullable|string|max:50',
            // 'trang_thai' => 'required|in:đang chiếu,sắp chiếu,đã kết thúc,bị hủy',
            'the_loai_ids' => 'required|array',
            'the_loai_ids.*' => 'exists:the_loai_phims,id',
            'dinh_dang_ids' => 'required|array',
            'dinh_dang_ids.*' => 'exists:dinh_dang_phims,id',
            'phu_de_ids' => 'required|array',
            'phu_de_ids.*' => 'exists:phu_de_phims,id',
            'chi_nhanh_ids' => 'required|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
            'rap_phim_ids' => 'array',
            'rap_phim_ids.*' => 'exists:rap_phims,id',
        ]);

        $data = $request->except(['poster', 'the_loai_ids', 'dinh_dang_ids', 'phu_de_ids', 'chi_nhanh_ids', 'rap_phim_ids']);

        // Tính trạng thái dựa trên ngày phát hành và ngày kết thúc
        $today = Carbon::today();
        $ngayPhatHanh = $request->ngay_phat_hanh ? Carbon::parse($request->ngay_phat_hanh) : null;
        $ngayKetThuc = $request->ngay_ket_thuc ? Carbon::parse($request->ngay_ket_thuc) : null;

        if ($ngayPhatHanh && $ngayPhatHanh->isFuture()) {
            $data['trang_thai'] = 'sắp chiếu';
        } elseif ($ngayPhatHanh && $ngayKetThuc && $today->between($ngayPhatHanh, $ngayKetThuc)) {
            $data['trang_thai'] = 'đang chiếu';
        } elseif ($ngayKetThuc && $today->gt($ngayKetThuc)) {
            $data['trang_thai'] = 'đã kết thúc';
        } else {
            $data['trang_thai'] = 'sắp chiếu'; // Mặc định nếu thiếu thông tin ngày
        }

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $phim = Phim::create($data);
        $phim->theLoais()->attach($request->the_loai_ids);
        $phim->dinhDangs()->attach($request->dinh_dang_ids);
        $phim->phuDes()->attach($request->phu_de_ids);
        $phim->chiNhanhs()->attach($request->chi_nhanh_ids);
        $phim->rapPhims()->attach($request->rap_phim_ids);

        return redirect()->route('admin.phim.index')
            ->with('success', 'Phim đã được tạo thành công!');
    }

    public function show($id)
    {
        $phim = Phim::with('theLoais', 'dinhDangs', 'phuDes', 'chiNhanhs', 'rapPhims')->findOrFail($id);
        return view('admin.phim.show', compact('phim'));
    }

    public function edit($id)
    {
        $phim = Phim::with('theLoais', 'dinhDangs', 'chiNhanhs', 'rapPhims', 'phuDes')->findOrFail($id);

        $selectedTheLoais = $phim->theLoais->pluck('id')->toArray();
        $selectedDinhDangs = $phim->dinhDangs->pluck('id')->toArray();
        $selectedPhuDes = $phim->phuDes->pluck('id')->toArray();
        $selectedChiNhanhs = $phim->chiNhanhs->pluck('id')->toArray();
        $selectedRapPhims = $phim->rapPhims->pluck('id')->toArray();

        //  Luôn lấy full dữ liệu cho tất cả
        $theLoaiPhims = TheLoaiPhim::where('trang_thai', 'hoạt động')->get();
        $dinhDangPhims = DinhDangPhim::where('trang_thai', 'hoạt động')->get();
        $phuDePhims = PhuDePhim::where('trang_thai', 'hoạt động')->get();
        $chiNhanhs = ChiNhanh::where('trang_thai', 'hoat_dong')->get();
        $rapPhims = RapPhim::where('trang_thai', 'đang hoạt động')->get();

        //  Nếu admin chi nhánh -> chỉ cho phép rạp thuộc quyền
        if (Auth::user()->vai_tro_id == 2) {
            $rapPhims = RapPhim::whereHas('chiNhanh', function ($query) {
                $query->where('quan_ly_id', Auth::id());
            })->where('trang_thai', 'đang hoạt động')->get();
        }

        return view('admin.phim.edit', compact(
            'phim',
            'theLoaiPhims',
            'selectedTheLoais',
            'dinhDangPhims',
            'selectedDinhDangs',
            'phuDePhims',
            'selectedPhuDes',
            'chiNhanhs',
            'selectedChiNhanhs',
            'rapPhims',
            'selectedRapPhims'
        ));
    }

    public function update(Request $request, $id)
    {
        $phim = Phim::with(['chiNhanhs:id', 'rapPhims:id,chi_nhanh_id'])->findOrFail($id);

        if (Auth::user()->vai_tro_id == 1) {
            // === ADMIN TỔNG ===
            $this->validateAdmin($request);
            $data = $this->prepareData($request, $phim);
            $phim->update($data);

            // 1) Sync CHI NHÁNH theo form (admin tổng chỉ quản lý tới chi nhánh)
            $chiNhanhIds = $request->input('chi_nhanh_ids', []);
            $phim->chiNhanhs()->sync($chiNhanhIds);

            // 2) KHÔNG đụng vào rạp đã có, TRỪ rạp thuộc chi nhánh bị bỏ
            if (!empty($chiNhanhIds)) {
                // các rạp đang gán cho phim
                $currentRapIds = $phim->rapPhims->pluck('id')->toArray();

                // rạp nào thuộc chi nhánh KHÔNG còn nằm trong $chiNhanhIds thì gỡ
                $rapIdsToDetach = $phim->rapPhims
                    ->whereNotIn('chi_nhanh_id', $chiNhanhIds)
                    ->pluck('id')->toArray();

                if (!empty($rapIdsToDetach)) {
                    $phim->rapPhims()->detach($rapIdsToDetach);
                }
                // LƯU Ý: không gọi sync([]) cho rapPhims, để tránh xóa sạch khi form không gửi rap_phim_ids
            }

            // các quan hệ khác xử lý bình thường
            $phim->theLoais()->sync($request->input('the_loai_ids', []));
            $phim->dinhDangs()->sync($request->input('dinh_dang_ids', []));
            $phim->phuDes()->sync($request->input('phu_de_ids', []));
        } elseif (Auth::user()->vai_tro_id == 2) {
            // === ADMIN CHI NHÁNH ===
            // Chỉ được gán/bỏ rạp thuộc chi nhánh mình quản lý, không được đụng chi nhánh của phim
            $request->validate([
                'rap_phim_ids'   => 'required|array',
                'rap_phim_ids.*' => [
                    'exists:rap_phims,id',
                    function ($attr, $value, $fail) {
                        $rap = RapPhim::with('chiNhanh')->find($value);
                        if (!$rap || $rap->chiNhanh->quan_ly_id != Auth::id()) {
                            $fail("Rạp ID {$value} không thuộc quyền quản lý.");
                        }
                    }
                ],
            ]);

            $currentRapIds   = $phim->rapPhims()->pluck('rap_phims.id')->toArray();
            $allowedRapIds   = RapPhim::whereHas('chiNhanh', fn($q) => $q->where('quan_ly_id', Auth::id()))
                ->pluck('id')->toArray();

            // Giữ nguyên các rạp hiện có không thuộc quyền quản lý của admin chi nhánh
            $protectedRapIds = array_diff($currentRapIds, $allowedRapIds);

            // Tập mới = rạp được phép từ request + các rạp bảo vệ (ngoài quyền)
            $newRapIds = array_values(array_unique(array_merge(
                $protectedRapIds,
                $request->input('rap_phim_ids', [])
            )));

            $phim->rapPhims()->sync($newRapIds);

            // Không cho sửa chi nhánh ở vai trò 2
        } else {
            return redirect()->route('admin.phim.index')
                ->with('error', 'Bạn không có quyền cập nhật phim này.');
        }

        return redirect()->route('admin.phim.index')
            ->with('success', 'Phim đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $phim = Phim::findOrFail($id);

        // Sử dụng xóa mềm thay vì xóa hoàn toàn
        $phim->delete();

        return redirect()->route('admin.phim.index')
            ->with('success', 'Phim đã được xóa mềm thành công!');
    }

    // Thêm các phương thức mới để quản lý phim đã xóa mềm

    public function trash()
    {
        $trashedPhims = Phim::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('admin.phim.trash', compact('trashedPhims'));
    }

    public function restore($id)
    {
        $phim = Phim::onlyTrashed()->findOrFail($id);
        $phim->restore();

        return redirect()->route('admin.phim.trash')
            ->with('success', 'Phim đã được khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $phim = Phim::onlyTrashed()->findOrFail($id);

        // Xóa poster nếu có
        if ($phim->poster) {
            Storage::disk('public')->delete($phim->poster);
        }

        // Xóa quan hệ với thể loại vaf định dạng
        $phim->theLoais()->detach();
        $phim->dinhDangs()->detach();
        $phim->phuDes()->detach();
        $phim->chiNhanhs()->detach();
        $phim->rapPhims()->detach();

        // Xóa vĩnh viễn
        $phim->forceDelete();

        return redirect()->route('admin.phim.trash')
            ->with('success', 'Phim đã được xóa vĩnh viễn!');
    }
    protected function validateAdmin(Request $request)
    {
        $request->validate([
            'ten_phim' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'dao_dien' => 'nullable|string|max:255',
            'dien_vien' => 'nullable|string',
            'thoi_luong' => 'nullable|integer|min:1',
            'ngay_phat_hanh' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date',
            'trailer' => 'nullable|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ngon_ngu' => 'nullable|string|max:50',
            'quoc_gia' => 'nullable|string|max:50',
            'do_tuoi' => 'nullable|string|max:50',
            'the_loai_ids' => 'required|array',
            'the_loai_ids.*' => 'exists:the_loai_phims,id',
            'dinh_dang_ids' => 'required|array',
            'dinh_dang_ids.*' => 'exists:dinh_dang_phims,id',
            'phu_de_ids' => 'required|array',
            'phu_de_ids.*' => 'exists:phu_de_phims,id',
            'chi_nhanh_ids' => 'required|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
            'rap_phim_ids' => 'array',
            'rap_phim_ids.*' => 'exists:rap_phims,id',
        ]);
    }

    protected function prepareData(Request $request, $phim)
    {
        $data = $request->except(['poster', 'the_loai_ids', 'dinh_dang_ids', 'phu_de_ids', 'chi_nhanh_ids', 'rap_phim_ids']);

        // Tính trạng thái phim
        $today = Carbon::today();
        $ngayPhatHanh = $request->ngay_phat_hanh ? Carbon::parse($request->ngay_phat_hanh) : null;
        $ngayKetThuc = $request->ngay_ket_thuc ? Carbon::parse($request->ngay_ket_thuc) : null;

        if ($ngayPhatHanh && $ngayPhatHanh->isFuture()) {
            $data['trang_thai'] = 'sắp chiếu';
        } elseif ($ngayPhatHanh && $ngayKetThuc && $today->between($ngayPhatHanh, $ngayKetThuc)) {
            $data['trang_thai'] = 'đang chiếu';
        } elseif ($ngayKetThuc && $today->gt($ngayKetThuc)) {
            $data['trang_thai'] = 'đã kết thúc';
        } else {
            $data['trang_thai'] = 'sắp chiếu';
        }

        if ($request->hasFile('poster')) {
            if ($phim->poster) {
                Storage::disk('public')->delete($phim->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        return $data;
    }

    protected function syncAllRelations(Phim $phim, Request $request)
    {
        $phim->theLoais()->sync($request->the_loai_ids);
        $phim->dinhDangs()->sync($request->dinh_dang_ids);
        $phim->phuDes()->sync($request->phu_de_ids);
        $phim->chiNhanhs()->sync($request->chi_nhanh_ids);
        $phim->rapPhims()->sync($request->rap_phim_ids ?? []);
    }
}
