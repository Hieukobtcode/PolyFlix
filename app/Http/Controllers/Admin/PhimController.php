<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiNhanh;
use App\Models\DinhDangPhim;
use App\Models\PhuDePhim;
use App\Models\Phim;
use App\Models\RapPhim;
use App\Models\TheLoaiPhim;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhimController extends Controller
{
    public function index()
    {
        $phims = Phim::orderBy('create_at', 'desc')->paginate(10);
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
        $theLoaiPhims = TheLoaiPhim::where('trang_thai', 'hoạt động')->get();
        $selectedTheLoais = $phim->theLoais->pluck('id')->toArray();
        $dinhDangPhims = DinhDangPhim::where('trang_thai', 'hoạt động')->get();
        $selectedDinhDangs = $phim->dinhDangs->pluck('id')->toArray();
        $phuDePhims = PhuDePhim::where('trang_thai', 'hoạt động')->get();
        $selectedPhuDes = $phim->phuDes->pluck('id')->toArray();
        $chiNhanhs = ChiNhanh::where('trang_thai', 'hoat_dong')->get();
        $selectedChiNhanhs = $phim->chiNhanhs->pluck('id')->toArray();
        $rapPhims = RapPhim::where('trang_thai', 'đang hoạt động')->get();
        $selectedRapPhims = $phim->rapPhims->pluck('id')->toArray();

        return view('admin.phim.edit', compact('phim', 'theLoaiPhims', 'selectedTheLoais', 'dinhDangPhims', 'selectedDinhDangs', 'phuDePhims', 'selectedPhuDes', 'chiNhanhs', 'selectedChiNhanhs', 'rapPhims', 'selectedRapPhims'));
    }

    public function update(Request $request, $id)
    {
        $phim = Phim::findOrFail($id);

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
            if ($phim->poster) {
                Storage::disk('public')->delete($phim->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $phim->update($data);
        $phim->theLoais()->sync($request->the_loai_ids);
        $phim->dinhDangs()->sync($request->dinh_dang_ids);
        $phim->phuDes()->sync($request->phu_de_ids);
        $phim->chiNhanhs()->sync($request->chi_nhanh_ids);
        $phim->rapPhims()->sync($request->chi_nhanh_ids);

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
}