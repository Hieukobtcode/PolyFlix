<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiNhanh;
use Illuminate\Http\Request;
use App\Models\DoAn;
use App\Models\DanhMucDoAn;

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

        $doAns = $query->paginate(10); // 👈 Đây là điểm quan trọng

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

        $path = null;
        if ($request->hasFile('hinh_anh')) {
            $path = $request->file('hinh_anh')->store('hinh_anh', 'public');
        }

        $doAn = DoAn::create([
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'hinh_anh' => $path,
            'trang_thai' => $request->trang_thai ?? 'hien',
            'gia' => $request->gia,
            'danh_muc_id' => $request->danh_muc_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Gắn nhiều chi nhánh
        $doAn->chiNhanhs()->attach($request->chi_nhanh_ids);

        return redirect()->route('admin.do-an.index')->with('success', 'Đã thêm món ăn thành công!');
    }

   public function edit(DoAn $doAn)
{
    $danhMucs = DanhMucDoAn::all();
    $chiNhanhs = ChiNhanh::all(); // hoặc \App\Models\ChiNhanh::all()
    return view('admin.do-an.edit', compact('doAn', 'danhMucs', 'chiNhanhs'));
}

    public function update(Request $request, DoAn $doAn)
{
    $request->validate([
        'tieu_de' => 'required|string|max:255',
        'gia' => 'required|numeric|min:0',
        'danh_muc_id' => 'required|exists:danh_muc_do_ans,id',
        'chi_nhanh_ids' => 'required|array',
        'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id',
        'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Nếu có upload ảnh mới, lưu lại
    $path = $doAn->hinh_anh;
    if ($request->hasFile('hinh_anh')) {
        $path = $request->file('hinh_anh')->store('hinh_anh', 'public');
    }

    // Cập nhật thông tin món ăn
    $doAn->update([
        'tieu_de' => $request->tieu_de,
        'noi_dung' => $request->noi_dung,
        'hinh_anh' => $path,
        'trang_thai' => $request->trang_thai ?? 'hien',
        'gia' => $request->gia,
        'danh_muc_id' => $request->danh_muc_id,
    ]);

    // Đồng bộ lại chi nhánh (quan hệ many-to-many)
    $doAn->chiNhanhs()->sync($request->chi_nhanh_ids);

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
