<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChiNhanh;

class ChiNhanhController extends Controller
{
    public function index(Request $request)
    {
        $query = ChiNhanh::query();

        // Lọc theo từ khóa
        if ($request->has('keyword') && $request->keyword) {
            $query->where('ten_chi_nhanh', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('trang_thai', $request->status);
        }

        $chiNhanhs = $query->paginate(10);

        return view('admin.chi-nhanh.index', compact('chiNhanhs'));
    }


    public function create()
    {
        return view('admin.chi-nhanh.create');  // Sửa thành 'admin.chi-nhanh.create'
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_chi_nhanh' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'quan_ly_id' => 'required|integer',
            'trang_thai' => 'required|in:hoat_dong,tam_dung,dong_cua',
        ]);

        ChiNhanh::create($request->all());

        return redirect()->route('admin.chi-nhanh.index')->with('success', 'Thêm chi nhánh thành công');  // Sửa thành 'admin.chi-nhanhs.index'
    }

    public function edit($id)
    {
        $chiNhanh = ChiNhanh::findOrFail($id);
        return view('admin.chi-nhanh.edit', compact('chiNhanh'));  // Sửa thành 'admin.chi-nhanh.edit'
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_chi_nhanh' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'quan_ly_id' => 'required|integer',
            'trang_thai' => 'required|in:hoat_dong,tam_dung,dong_cua',
        ]);

        $chiNhanh = ChiNhanh::findOrFail($id);
        $chiNhanh->update($request->all());

        return redirect()->route('admin.chi-nhanh.index')->with('success', 'Cập nhật chi nhánh thành công');  // Sửa thành 'admin.chi-nhanhs.index'
    }
  public function show($id)
{
    $chiNhanh = ChiNhanh::with('RapPhim')->findOrFail($id);
    return view('admin.chi-nhanh.show', compact('chiNhanh'));
}



    public function destroy($id)
    {
        ChiNhanh::findOrFail($id)->delete();
        return redirect()->route('admin.chi-nhanh.index')->with('success', 'Xóa chi nhánh thành công');  // Sửa thành 'admin.chi-nhanhs.index'
    }
}
