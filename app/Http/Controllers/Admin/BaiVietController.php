<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaiVietRequest;
use App\Models\BaiViet;
use Illuminate\Http\Request;

class BaiVietController extends Controller
{
    // Hiển thị danh sách bài viết
    public function index(Request $request)
    {
        $query = BaiViet::query();
    
        if ($request->filled('keyword')) {
            $query->where('tieu_de', 'like', '%' . $request->keyword . '%');
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        $baiViets = $query->orderByDesc('ngay_tao')->paginate(10);
    
        return view('admin.bai-viet.index', compact('baiViets'));
    }

    // Hiển thị form tạo bài viết mới
    public function create()
    {
        return view('admin.bai-viet.create');
    }

    // Lưu bài viết mới
    public function store(BaiVietRequest $request)
{
    try {
        $data = $request->validated();

        // Xử lý ảnh nếu có
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('hinh_anhs', 'public');
        }

        $data['ngay_tao'] = now(); // Nếu không dùng timestamps mặc định

        BaiViet::create($data);

        return redirect()->route('admin.bai-viet.index')
            ->with('success', 'Bài viết đã được tạo thành công!');
    } catch (\Exception $e) {
        \Log::error('Lỗi khi tạo bài viết: ' . $e->getMessage());
        return back()->withInput()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
    }
}

    // Xem chi tiết bài viết
    public function show($id)
    {
        $baiViet = BaiViet::findOrFail($id);
        return view('admin.bai-viet.show', compact('baiViet'));
    }

    // Hiển thị form chỉnh sửa bài viết
    public function edit($id)
    {
        $baiViet = BaiViet::findOrFail($id);
        return view('admin.bai-viet.edit', compact('baiViet'));
    }

    // Cập nhật bài viết
    public function update(BaiVietRequest $request, $id)
{
    
    $baiViet = BaiViet::findOrFail($id);
    $data['ngay_cap_nhat'] = now(); 
    $data = $request->only(['tieu_de', 'noi_dung', 'status']);
    
    // Nếu có file mới được upload
    if ($request->hasFile('hinh_anh')) {
        if ($baiViet->hinh_anh) {
            Storage::disk('public')->delete($baiViet->hinh_anh);
        }
        $data['hinh_anh'] = $request->file('hinh_anh')->store('hinh_anhs', 'public');
    }

    // Cập nhật dữ liệu
    $baiViet->update($data);

    return redirect()->route('admin.bai-viet.index')
        ->with('success', 'Bài viết đã được cập nhật thành công!');
}


    // Xoá bài viết
    public function destroy($id)
    {
        $baiViet = BaiViet::findOrFail($id);

        // Kiểm tra ràng buộc (ví dụ như comment)
        if (method_exists($baiViet, 'comments') && $baiViet->comments()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xóa bài viết này vì đang có bình luận!');
        }

        $baiViet->delete();

        return redirect()->route('admin.bai-viet.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }
}
