<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('vi_tri')) {
            $query->where('vi_tri', $request->vi_tri);
        }
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $banners = $query->paginate(10)->appends(request()->query());
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hinh_anh' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'duong_dan' => 'nullable|url|max:255',
            'vi_tri' => 'required|string|max:100',
            'trang_thai' => 'required|boolean',
        ]);

        if ($request->hasFile('hinh_anh')) {
            $imagePath = $request->file('hinh_anh')->store('images/banners', 'public');
            $data['hinh_anh'] = $imagePath;
        }

        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công!');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $data = $request->validate([
            'hinh_anh' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'duong_dan' => 'nullable|url|max:255',
            'vi_tri' => 'required|string|max:100',
            'trang_thai' => 'required|boolean',
        ]);

        if ($request->hasFile('hinh_anh')) {
            $imagePath = $request->file('hinh_anh')->store('images/banners', 'public');
            if ($banner->hinh_anh) {
                Storage::disk('public')->delete($banner->hinh_anh);
            }
            $data['hinh_anh'] = $imagePath;
        }

        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Đã chuyển banner vào thùng rác!');
    }

    public function trash()
    {
        $banners = Banner::onlyTrashed()->paginate(10);
        return view('admin.banners.trash', compact('banners'));
    }

    public function restore($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();
        return redirect()->route('admin.banners.trash')->with('success', 'Khôi phục banner thành công!');
    }

    public function forceDelete($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        if ($banner->hinh_anh) {
            Storage::disk('public')->delete($banner->hinh_anh);
        }
        $banner->forceDelete();
        return redirect()->route('admin.banners.trash')->with('success', 'Xóa vĩnh viễn banner thành công!');
    }
}
