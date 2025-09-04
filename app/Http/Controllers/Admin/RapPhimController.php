<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\LoaiGhe;
use App\Models\RapPhim;
use App\Models\ChiNhanh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RapPhimController extends Controller
{
        public function index(Request $request)
    {
        $query = RapPhim::query();

        // ✅ Quản lý chi nhánh chỉ thấy rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2) {
            $query->whereHas('chiNhanh', function ($q) {
                $q->where('quan_ly_id', Auth::id());
            });
        }

        // ✅ Quản lý rạp chỉ thấy rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3) {
            $query->where('quan_ly_id', Auth::id());
        }

        if ($request->has('keyword') && $request->keyword) {
            $query->where('ten_rap', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('status') && $request->status) {
            $query->where('trang_thai', $request->status);
        }

        $rapPhims = $query->paginate(10);

        return view('admin.rap-phim.index', compact('rapPhims'));
    }

    public function create()
    {
        $id = request()->chiNhanhId;
        $chiNhanh = ChiNhanh::findOrFail($id);

        //  Quản lý chi nhánh chỉ tạo rạp trong chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.index')
                ->with('error', 'Bạn không có quyền tạo rạp cho chi nhánh này.');
        }

        return view('admin.rap-phim.create', compact('chiNhanh'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_rap' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'trang_thai' => 'required|in:đang hoạt động,bảo trì,đã đóng',
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
        ]);

        $chiNhanh = ChiNhanh::findOrFail($validated['chi_nhanh_id']);

        // Quản lý chi nhánh chỉ lưu rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.index')
                ->with('error', 'Bạn không có quyền thêm rạp cho chi nhánh này.');
        }

        RapPhim::create($validated);

        return redirect()
            ->route('admin.chi-nhanh.show', ['chi_nhanh' => $validated['chi_nhanh_id']])
            ->with('success', 'Thêm rạp chiếu thành công');
    }

    public function show($id)
    {
        $loaiGhes = LoaiGhe::all();
        $rapPhim = RapPhim::with(['phongChieus'])->findOrFail($id);

        //  Quản lý chi nhánh chỉ xem rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $rapPhim->chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.index')
                ->with('error', 'Bạn không có quyền xem rạp này.');
        }

        //  Quản lý rạp chỉ xem rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3 && $rapPhim->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.show',$rapPhim->chi_nhanh_id)
                ->with('error', 'Bạn không có quyền xem rạp này.');
        }

        return view('admin.rap-phim.show', compact('rapPhim', 'loaiGhes'));
    }

    public function edit($id)
    {
        $rapPhim = RapPhim::findOrFail($id);
        $chiNhanhs = ChiNhanh::all();

        //  Quản lý chi nhánh chỉ sửa rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $rapPhim->chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa rạp này.');
        }

        //  Quản lý rạp chỉ sửa rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3 && $rapPhim->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa rạp này.');
        }

        return view('admin.rap-phim.edit', compact('rapPhim', 'chiNhanhs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
            'ten_rap' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'trang_thai' => 'required|in:đang hoạt động,bảo trì,đã đóng',
        ]);

        $rapPhim = RapPhim::findOrFail($id);

        //  Quản lý chi nhánh chỉ cập nhật rạp thuộc chi nhánh mình quản lý
        if (Auth::user()->vai_tro_id == 2 && $rapPhim->chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền cập nhật rạp này.');
        }

        //  Quản lý rạp chỉ cập nhật rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3 && $rapPhim->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền cập nhật rạp này.');
        }

        $rapPhim->update($request->all());

        return redirect()->route('admin.chi-nhanh.show', $rapPhim->chi_nhanh_id)
            ->with('success', 'Cập nhật rạp chiếu thành công');
    }


    public function destroy($id)
    {
        RapPhim::findOrFail($id)->delete();

        return redirect()->route('admin.rap-phim.index')->with('success', 'Xóa rạp chiếu thành công');
    }
    public function showStaff(Request $request, $rap_id)
    {
        $query = User::query()->where('vai_tro_id', 4)
        ->where('rap_id', $rap_id);

        if ($request->has('keyword') && $request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('trang_thai', $request->status);
        }

        // nếu muốn lọc theo rạp phim
        if ($request->has('rap_id') && $request->rap_id) {
            $query->where('rap_id', $request->rap_id);
        }
        $rapPhim = RapPhim::findOrFail($rap_id);

        $staffs = $query->paginate(10);

        return view('admin.rap-phim.show-staff', compact('staffs', 'rapPhim'));
    }
    public function addStaff(Request $request, $id)
    {
        $rapPhim = RapPhim::findOrFail($id);



        //  Quản lý rạp chỉ thêm nhân viên cho rạp mình quản lý
        if (Auth::user()->vai_tro_id == 3 && $rapPhim->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.rap-phim.index')
                ->with('error', 'Bạn không có quyền thêm nhân viên cho rạp này.');
        }

        // Logic to add staff to the cinema
        // For example, you might have a many-to-many relationship between RapPhim and User models
        // $rapPhim->staff()->attach($request->user_id);

            return view('admin.rap-phim.add-staff', compact('rapPhim'));
    }
    public function storeStaff(Request $request)
    {

        $request->validate([
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'rap_id' => 'required|exists:rap_phims,id',
    ]);
    $rapPhim = RapPhim::findOrFail($request->rap_id);

    // Tạo user mới
    $user = User::create([
        'name' => $rapPhim->ten_rap,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'vai_tro_id' => 4, // nhân viên
        'rap_id' => $rapPhim->id
    ]);
    // dd($user);

    return redirect()->route('admin.rap-phim.show-staff', $rapPhim->id)
                     ->with('success', 'Thêm nhân viên thành công!');
    }
public function updateStatus(Request $request, $id)
{
    $staff = User::where('vai_tro_id', 4)->findOrFail($id);

    // Chỉ nhận giá trị hợp lệ
    $validated = $request->validate([
        'trang_thai' => 'required|in:Active,Block',
    ]);

    $staff->trang_thai = $validated['trang_thai']; // Lưu Active hoặc Block
    $staff->save();

    return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
}

    public function trash()
    {
        $rapPhims = RapPhim::onlyTrashed()->paginate(10);
        return view('admin.rap-phim.trash', compact('rapPhims'));
    }

    public function restore($id)
    {
        $rapPhim = RapPhim::onlyTrashed()->findOrFail($id);
        $rapPhim->restore();
        return redirect()->route('admin.rap-phim.trash')->with('success', 'Khôi phục rạp chiếu thành công');
    }

    public function forceDelete($id)
    {
        $rapPhim = RapPhim::onlyTrashed()->findOrFail($id);
        $rapPhim->forceDelete();
        return redirect()->route('admin.rap-phim.trash')->with('success', 'Xóa vĩnh viễn rạp chiếu thành công');
    }
}