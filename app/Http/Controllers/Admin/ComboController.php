<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiNhanh;
use Illuminate\Http\Request;
use App\Models\Combo;
use App\Models\DoAn;

class ComboController extends Controller
{
    public function index()
{
    $combos = Combo::with('chiNhanhs')->paginate(10);
    return view('admin.combo.index', compact('combos'));
}

    public function create()
{
    $doAns = DoAn::all();
    $chiNhanhs = ChiNhanh::all(); // 👈 dòng quan trọng

    return view('admin.combo.create', compact('doAns', 'chiNhanhs'));
}

    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'gia' => 'required|numeric|min:0',
            'gia_combo' => 'nullable|numeric|min:0',
            'do_an_ids' => 'array',
            'do_an_ids.*' => 'exists:do_ans,id',
            'chi_nhanh_ids' => 'required|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Xử lý hình ảnh (nếu có)
        $path = null;
        if ($request->hasFile('hinh_anh')) {
            $path = $request->file('hinh_anh')->store('combos', 'public');
        }

        // Tạo combo
        $combo = Combo::create([
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'hinh_anh' => $path,
            'trang_thai' => $request->trang_thai ?? 'hien',
            'gia' => $request->gia,
            'gia_combo' => $request->gia_combo ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Gán món ăn
        if ($request->has('do_an_ids')) {
            $combo->doAns()->sync($request->do_an_ids);
        }

        // Gán chi nhánh
        if ($request->has('chi_nhanh_ids')) {
            $combo->chiNhanhs()->attach($request->chi_nhanh_ids);
        }
        $combo->chiNhanhs()->sync($request->chi_nhanh_ids); // 👈 dùng sync thay cho attach
        return redirect()->route('admin.combos.index')->with('success', 'Đã tạo combo thành công!');
    }


   public function edit(Combo $combo)
{
    $doAns = DoAn::all();
    $chiNhanhs = ChiNhanh::all();

    return view('admin.combo.edit', compact('combo', 'doAns', 'chiNhanhs'));
}


 public function update(Request $request, Combo $combo)
{
    $request->validate([
        'tieu_de' => 'required|string|max:255',
        'gia' => 'required|numeric|min:0',
        'gia_combo' => 'nullable|numeric|min:0',
        'do_an_ids' => 'array',
        'do_an_ids.*' => 'exists:do_ans,id',
        'chi_nhanh_ids' => 'required|array',
        'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
        'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Cập nhật hình ảnh nếu có
    $path = $combo->hinh_anh;
    if ($request->hasFile('hinh_anh')) {
        $path = $request->file('hinh_anh')->store('combos', 'public');
    }

    $combo->update([
        'tieu_de' => $request->tieu_de,
        'noi_dung' => $request->noi_dung,
        'hinh_anh' => $path,
        'trang_thai' => $request->trang_thai ?? 'hien',
        'gia' => $request->gia,
        'gia_combo' => $request->gia_combo ?? 0,
    ]);

    $combo->doAns()->sync($request->do_an_ids ?? []);
    $combo->chiNhanhs()->sync($request->chi_nhanh_ids);

    return redirect()->route('admin.combos.index')->with('success', 'Đã cập nhật combo!');
}



    public function destroy(Combo $combo)
    {
        $combo->doAns()->detach();
        $combo->delete();
        return redirect()->route('admin.combos.index')->with('success', 'Đã xóa combo.');
    }
}
