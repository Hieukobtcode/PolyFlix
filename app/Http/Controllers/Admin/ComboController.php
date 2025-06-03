<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Combo;
use App\Models\DoAn;

class ComboController extends Controller
{
    public function index()
    {
        $combos = Combo::with('doAns')->paginate(10);
        return view('admin.combo.index', compact('combos'));
    }

    public function create()
    {
        $doAns = DoAn::all();
        return view('admin.combo.create', compact('doAns'));
    }

    public function store(Request $request)
{
    $request->validate([
        'tieu_de' => 'required|string|max:255',
        'gia' => 'required|numeric|min:0',
        'gia_combo' => 'nullable|numeric|min:0',
        'do_an_ids' => 'array',
    ]);

    $combo = Combo::create([
        'tieu_de' => $request->tieu_de,
        'noi_dung' => $request->noi_dung,
        'hinh_anh' => $request->hinh_anh,
        'trang_thai' => $request->trang_thai ?? 'hien',
        'gia' => $request->gia,
        'gia_combo' => $request->gia_combo ?? 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    if ($request->has('do_an_ids')) {
        $combo->doAns()->sync($request->do_an_ids);
    }

    return redirect()->route('admin.combos.index')->with('success', 'Đã tạo combo thành công!');
}


    public function edit(Combo $combo)
    {
        $doAns = DoAn::all();
        return view('admin.combo.edit', compact('combo', 'doAns'));
    }

   public function update(Request $request, Combo $combo)
{
    $request->validate([
        'tieu_de' => 'required|string|max:255',
        'gia' => 'required|numeric|min:0',
        'gia_combo' => 'nullable|numeric|min:0',
        'do_an_ids' => 'array',
    ]);

    $combo->update([
        'tieu_de' => $request->tieu_de,
        'noi_dung' => $request->noi_dung,
        'hinh_anh' => $request->hinh_anh,
        'trang_thai' => $request->trang_thai ?? 'hien',
        'gia' => $request->gia,
        'gia_combo' => $request->gia_combo ?? 0,
        'updated_at' => now(),
    ]);

    $combo->doAns()->sync($request->do_an_ids ?? []);

    return redirect()->route('admin.combos.index')->with('success', 'Đã cập nhật combo!');
}


    public function destroy(Combo $combo)
    {
        $combo->doAns()->detach();
        $combo->delete();
        return redirect()->route('admin.combos.index')->with('success', 'Đã xóa combo.');
    }
}
