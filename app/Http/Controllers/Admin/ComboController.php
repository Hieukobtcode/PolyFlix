<?php
namespace App\Http\Controllers\Admin;

use App\Models\DoAn;
use App\Models\Combo;
use App\Models\RapPhim;
use App\Models\ChiNhanh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ComboController extends Controller
{
    public function index()
    {
        $combos = Combo::with(['chiNhanhs', 'rapPhims'])->paginate(10);
        return view('admin.combo.index', compact('combos'));
    }

    public function create()
    {
        $doAns = DoAn::all();
        $chiNhanhs = ChiNhanh::with('rapPhims')->get(); // Lấy luôn các rạp của chi nhánh
        

        return view('admin.combo.create', compact('chiNhanhs', 'doAns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'gia_combo' => 'required|numeric|min:0',
            'do_ans' => 'nullable|array',
            'do_ans.*.so_luong' => 'nullable|integer|min:1',
            'chi_nhanh_ids' => 'nullable|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
            'rap_phim_ids' => 'nullable|array', // bỏ rap_phim_ids.* vì dữ liệu là mảng lồng
        ]);

        $combo = Combo::create([
            'tieu_de' => $validated['tieu_de'],
            'noi_dung' => $request->noi_dung,
            'gia' => 0, // tính tổng giá bên dưới
            'gia_combo' => $validated['gia_combo'],
            'trang_thai' => $request->trang_thai ?? 'hien',
            'hinh_anh' => $request->hinh_anh ? $request->file('hinh_anh')->store('uploads', 'public') : null,
        ]);

        $tongGia = 0;

        if ($request->has('do_ans')) {
            foreach ($request->do_ans as $doAnId => $info) {
                if (!empty($info['selected'])) {
                    $soLuong = intval($info['so_luong'] ?? 1);
                    $combo->doAns()->attach($doAnId, ['so_luong' => $soLuong]);

                    $giaMon = DoAn::find($doAnId)?->gia ?? 0;
                    $tongGia += $giaMon * $soLuong;
                }
            }
        }

        if (!empty($validated['chi_nhanh_ids'])) {
            $combo->chiNhanhs()->sync($validated['chi_nhanh_ids']);
        }

        if (!empty($validated['rap_phim_ids'])) {
            // Flatten array để tránh lỗi SQL
            $flattenRaps = collect($validated['rap_phim_ids'])->flatten()->toArray();
            $combo->rapPhims()->sync($flattenRaps);
        }

        return redirect()->route('admin.combos.index')->with('success', 'Thêm combo thành công!');
    }






    public function edit($id)
    {
        $combo = Combo::with(['doAns', 'chiNhanhs', 'rapPhims'])->findOrFail($id);
        $doAns = DoAn::all();
        $chiNhanhs = ChiNhanh::all();

        $rapPhimSelected = $combo->rapPhims->pluck('id')->toArray(); // 🆕 Danh sách rạp đã chọn
        $chiNhanhSelected = $combo->chiNhanhs->pluck('id')->toArray(); // 🆕 Danh sách chi nhánh đã chọn

        if (Auth::user()->vai_tro_id == 2) {
    // Admin chi nhánh: chỉ lấy rạp thuộc các chi nhánh họ quản lý
    $chiNhanhIds = ChiNhanh::where('quan_ly_id', Auth::id())->pluck('id')->toArray();

    $rapPhims = RapPhim::whereIn('chi_nhanh_id', $chiNhanhIds)->get();

    $rapPhimSelected = $combo->rapPhims()
        ->whereIn('chi_nhanh_id', $chiNhanhIds)
        ->pluck('rap_phims.id')
        ->toArray();
} else {
    // Admin tổng: lấy tất cả rạp
    $rapPhims = RapPhim::all();

    $rapPhimSelected = $combo->rapPhims()
        ->pluck('rap_phims.id')
        ->toArray();
}


        return view('admin.combo.edit', compact(
            'combo',
            'doAns',
            'chiNhanhs',
            'rapPhims',
            'rapPhimSelected',
            'chiNhanhSelected'
        ));
    }



public function update(Request $request, $id)
{
    $combo = Combo::findOrFail($id);

    $validated = $request->validate([
        'tieu_de' => 'required|string|max:255',
        'gia_combo' => 'required|numeric|min:0',
        'do_ans' => 'nullable|array',
        'do_ans.*.so_luong' => 'nullable|integer|min:1',
    ]);

    $combo->update([
        'tieu_de' => $validated['tieu_de'],
        'noi_dung' => $request->noi_dung,
        'gia_combo' => $validated['gia_combo'],
        'trang_thai' => $request->trang_thai ?? 'hien',
        'hinh_anh' => $request->hinh_anh
            ? $request->file('hinh_anh')->store('uploads', 'public')
            : $combo->hinh_anh,
    ]);

    // Xử lý món ăn
    $combo->doAns()->sync([]);
    $tongGia = 0;
    if ($request->has('do_ans')) {
        foreach ($request->do_ans as $doAnId => $info) {
            if (!empty($info['selected'])) {
                $soLuong = intval($info['so_luong'] ?? 1);
                $combo->doAns()->attach($doAnId, ['so_luong' => $soLuong]);

                $giaMon = DoAn::find($doAnId)?->gia ?? 0;
                $tongGia += $giaMon * $soLuong;
            }
        }
    }

    // ✅ Phân quyền chỉnh sửa
    if (Auth::user()->vai_tro_id == 1) {
        // ✅ Sync chi nhánh mới
        $newChiNhanhIds = $request->input('chi_nhanh_ids', []);
        $combo->chiNhanhs()->sync($newChiNhanhIds);

        // ✅ Xóa rạp thuộc các chi nhánh không còn trong danh sách
        $rapsToRemove = RapPhim::whereNotIn('chi_nhanh_id', $newChiNhanhIds)
            ->pluck('id')
            ->toArray();

        if (!empty($rapsToRemove)) {
            $combo->rapPhims()->detach($rapsToRemove);
        }

        // ✅ Không động vào rạp thuộc chi nhánh còn lại
    }

    if (Auth::user()->vai_tro_id == 2) {
        // Admin chi nhánh: chỉ chỉnh rạp thuộc chi nhánh họ quản lý
        $rapsAdmin = RapPhim::whereIn('chi_nhanh_id', function ($query) {
            $query->select('id')->from('chi_nhanhs')->where('quan_ly_id', Auth::id());
        })->pluck('id')->toArray();

        $rapsToSync = array_intersect($request->rap_phim_ids ?? [], $rapsAdmin);

        // Merge rạp cũ không thuộc quyền admin
        $rapsKeep = $combo->rapPhims()
                            ->whereNotIn('rap_phims.id', $rapsAdmin)
                            ->pluck('rap_phims.id')
                            ->toArray();

        $finalRaps = array_unique(array_merge($rapsKeep, $rapsToSync));

        $combo->rapPhims()->sync($finalRaps);
    }

    $combo->update(['gia' => $tongGia]);

    return redirect()->route('admin.combos.index')->with('success', 'Cập nhật combo thành công!');
}







    public function destroy(Combo $combo)
    {
        $combo->doAns()->detach();
        $combo->delete();
        return redirect()->route('admin.combos.index')->with('success', 'Đã xóa combo.');
    }
}
