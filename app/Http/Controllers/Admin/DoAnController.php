<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiNhanh;
use App\Models\DoAn;
use App\Models\DanhMucDoAn;
use App\Models\RapPhim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoAnController extends Controller
{
    public function index(Request $request)
    {
        $query = DoAn::with(['danhMuc', 'chiNhanhs']);

        if ($request->filled('keyword')) {
            $query->where('tieu_de', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $doAns = $query->paginate(10);

        return view('admin.do-an.index', compact('doAns'));
    }

    public function create()
    {
        $danhMucs = DanhMucDoAn::all();
        $chiNhanhs = ChiNhanh::all();
        return view('admin.do-an.create', compact('danhMucs', 'chiNhanhs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'gia' => 'required|numeric|min:0',
            'danh_muc_id' => 'required|exists:danh_muc_do_ans,id',
            'chi_nhanh_ids' => 'required|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->hasFile('hinh_anh') ?
            $request->file('hinh_anh')->store('hinh_anh', 'public') : null;

        $doAn = DoAn::create([
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'hinh_anh' => $path,
            'trang_thai' => $request->trang_thai ?? 'hien',
            'gia' => $request->gia,
            'danh_muc_id' => $request->danh_muc_id,
        ]);

        // Gắn nhiều chi nhánh
        $doAn->chiNhanhs()->attach($request->chi_nhanh_ids);

        return redirect()->route('admin.do-an.index')->with('success', 'Đã thêm món ăn thành công!');
    }

public function edit(DoAn $doAn)
{
    $danhMucs = DanhMucDoAn::all();

    if (Auth::user()->vai_tro_id == 1) {
        // Admin tổng: lấy toàn bộ
        $chiNhanhs = ChiNhanh::all();
        $rapPhims = RapPhim::with('chiNhanh')->get();
    } else {
        // Admin chi nhánh: chỉ lấy chi nhánh quản lý và rạp thuộc quyền
        $chiNhanhs = ChiNhanh::where('quan_ly_id', Auth::id())->get();
        $allowedChiNhanhIds = $chiNhanhs->pluck('id')->toArray();

        $rapPhims = RapPhim::with('chiNhanh')
            ->whereIn('chi_nhanh_id', $allowedChiNhanhIds)
            ->get();

        // Lọc rạp đã chọn chỉ giữ rạp thuộc quyền
        $doAn->load(['rapPhims' => function ($query) use ($allowedChiNhanhIds) {
            $query->whereIn('chi_nhanh_id', $allowedChiNhanhIds);
        }, 'chiNhanhs']);

        // Check món ăn có thuộc chi nhánh quản lý không
        $doAnChiNhanhIds = $doAn->chiNhanhs->pluck('id')->toArray();

        if (!array_intersect($allowedChiNhanhIds, $doAnChiNhanhIds)) {
            abort(403, 'Bạn không có quyền chỉnh sửa món ăn này.');
        }
    }

    return view('admin.do-an.edit', compact('doAn', 'danhMucs', 'chiNhanhs', 'rapPhims'));
}





public function update(Request $request, DoAn $doAn)
{
    $request->validate([
        'tieu_de' => 'required|string|max:255',
        'gia' => 'required|numeric|min:0',
        'danh_muc_id' => 'required|exists:danh_muc_do_ans,id',
        'chi_nhanh_ids' => 'nullable|array',
        'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
        'rap_phim_ids' => 'nullable|array',
        'rap_phim_ids.*' => 'exists:rap_phims,id',
        'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $path = $request->hasFile('hinh_anh')
        ? $request->file('hinh_anh')->store('hinh_anh', 'public')
        : $doAn->hinh_anh;

    $doAn->update([
        'tieu_de' => $request->tieu_de,
        'noi_dung' => $request->noi_dung,
        'hinh_anh' => $path,
        'trang_thai' => $request->trang_thai ?? 'hien',
        'gia' => $request->gia,
        'danh_muc_id' => $request->danh_muc_id,
    ]);

    if (Auth::user()->vai_tro_id == 1) {
        // ✅ Admin tổng: sync chi nhánh
        $newChiNhanhIds = $request->input('chi_nhanh_ids', []);
        $doAn->chiNhanhs()->sync($newChiNhanhIds);

        // ❌ Gỡ các rạp không còn thuộc chi nhánh đã chọn
        $rapsToRemove = RapPhim::whereNotIn('chi_nhanh_id', $newChiNhanhIds)
            ->pluck('rap_phims.id')->toArray(); // 🛠 Fix ambiguous

        if (!empty($rapsToRemove)) {
            $doAn->rapPhims()->detach($rapsToRemove);
        }

        // Gắn lại rạp được chọn (nếu có)
        if ($request->has('rap_phim_ids')) {
            $doAn->rapPhims()->syncWithoutDetaching($request->rap_phim_ids);
        }
    }

    if (Auth::user()->vai_tro_id == 2) {
        // ✅ Admin chi nhánh: chỉ có quyền với rạp thuộc chi nhánh mình quản lý
        $rapsAdmin = RapPhim::whereIn('chi_nhanh_id', function ($query) {
                $query->select('id')
                    ->from('chi_nhanhs')
                    ->where('quan_ly_id', Auth::id());
            })
            ->pluck('rap_phims.id') // 🛠 Fix ambiguous
            ->toArray();

        $rapsToSync = array_intersect($request->rap_phim_ids ?? [], $rapsAdmin);

        // Giữ lại các rạp admin không quản lý (để tránh xóa nhầm)
        $rapsKeep = $doAn->rapPhims()
            ->whereNotIn('rap_phims.id', $rapsAdmin) // 🛠 Fix ambiguous
            ->pluck('rap_phims.id')
            ->toArray();

        $finalRaps = array_unique(array_merge($rapsKeep, $rapsToSync));

        $doAn->rapPhims()->sync($finalRaps);
    }

    return redirect()->route('admin.do-an.index')->with('success', 'Cập nhật món ăn thành công!');
}


    public function show(DoAn $doAn)
    {
        return view('admin.do-an.show', compact('doAn'));
    }

    public function destroy(DoAn $doAn)
    {
        $doAn->delete();
        return redirect()->route('admin.do-an.index')->with('success', 'Đã xóa món ăn.');
    }
}
