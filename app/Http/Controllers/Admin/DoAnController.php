<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoAn;
use App\Models\DanhMucDoAn;

class DoAnController extends Controller
{
    public function index(Request $request)
{
    $query = DoAn::with('danhMuc');

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
        return view('admin.do-an.create', compact('danhMucs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'gia' => 'required|numeric|min:0',
            'danh_muc_id' => 'required|exists:danh_muc_do_ans,id',
        ]);

        DoAn::create([
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'hinh_anh' => $request->hinh_anh,
            'trang_thai' => $request->trang_thai ?? 'hien',
            'gia' => $request->gia,
            'danh_muc_id' => $request->danh_muc_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.do-an.index')->with('success', 'Đã thêm món ăn thành công!');
    }

    public function edit(DoAn $doAn)
    {
        $danhMucs = DanhMucDoAn::all();
        return view('admin.do-an.edit', compact('doAn', 'danhMucs'));
    }

    public function update(Request $request, DoAn $doAn)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'gia' => 'required|numeric|min:0',
            'danh_muc_id' => 'required|exists:danh_muc_do_ans,id',
        ]);

        $doAn->update([
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
            'hinh_anh' => $request->hinh_anh,
            'trang_thai' => $request->trang_thai ?? 'hien',
            'gia' => $request->gia,
            'danh_muc_id' => $request->danh_muc_id,
            'updated_at' => now(),
        ]);

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
