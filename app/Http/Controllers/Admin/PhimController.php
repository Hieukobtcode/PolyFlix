<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use App\Models\TheLoaiPhim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhimController extends Controller
{
    public function index()
    {
        $phims = Phim::orderBy('create_at', 'desc')->paginate(10);
        return view('admin.phim.index', compact('phims'));
    }

    public function create()
    {
        $theLoaiPhims = TheLoaiPhim::where('trang_thai', 'hoạt động')->get();
        return view('admin.phim.create', compact('theLoaiPhims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_phim' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'dao_dien' => 'nullable|string|max:255',
            'dien_vien' => 'nullable|string',
            'thoi_luong' => 'nullable|integer|min:1',
            'ngay_phat_hanh' => 'nullable|date',
            'trailer' => 'nullable|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ngon_ngu' => 'nullable|string|max:50',
            'quoc_gia' => 'nullable|string|max:50',
            'do_tuoi' => 'nullable|string|max:50',
            'trang_thai' => 'required|in:đang chiếu,sắp chiếu,đã kết thúc,bị hủy',
            'the_loai_ids' => 'required|array',
            'the_loai_ids.*' => 'exists:the_loai_phims,id',
        ]);

        $data = $request->except(['poster', 'the_loai_ids']);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $phim = Phim::create($data);
        $phim->theLoais()->attach($request->the_loai_ids);

        return redirect()->route('admin.phim.index')
            ->with('success', 'Phim đã được tạo thành công!');
    }

    public function show($id)
    {
        $phim = Phim::with('theLoais')->findOrFail($id);
        return view('admin.phim.show', compact('phim'));
    }

    public function edit($id)
    {
        $phim = Phim::with('theLoais')->findOrFail($id);
        $theLoaiPhims = TheLoaiPhim::where('trang_thai', 'hoạt động')->get();
        $selectedTheLoais = $phim->theLoais->pluck('id')->toArray();

        return view('admin.phim.edit', compact('phim', 'theLoaiPhims', 'selectedTheLoais'));
    }

    public function update(Request $request, $id)
    {
        $phim = Phim::findOrFail($id);

        $request->validate([
            'ten_phim' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'dao_dien' => 'nullable|string|max:255',
            'dien_vien' => 'nullable|string',
            'thoi_luong' => 'nullable|integer|min:1',
            'ngay_phat_hanh' => 'nullable|date',
            'trailer' => 'nullable|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ngon_ngu' => 'nullable|string|max:50',
            'quoc_gia' => 'nullable|string|max:50',
            'do_tuoi' => 'nullable|string|max:50',
            'trang_thai' => 'required|in:đang chiếu,sắp chiếu,đã kết thúc,bị hủy',
            'the_loai_ids' => 'required|array',
            'the_loai_ids.*' => 'exists:the_loai_phims,id',
        ]);

        $data = $request->except(['poster', 'the_loai_ids']);

        if ($request->hasFile('poster')) {
            if ($phim->poster) {
                Storage::disk('public')->delete($phim->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $phim->update($data);
        $phim->theLoais()->sync($request->the_loai_ids);

        return redirect()->route('admin.phim.index')
            ->with('success', 'Phim đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $phim = Phim::findOrFail($id);

        // Sử dụng xóa mềm thay vì xóa hoàn toàn
        $phim->delete();

        return redirect()->route('admin.phim.index')
            ->with('success', 'Phim đã được xóa mềm thành công!');
    }

    // Thêm các phương thức mới để quản lý phim đã xóa mềm

    public function trash()
    {
        $trashedPhims = Phim::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('admin.phim.trash', compact('trashedPhims'));
    }

    public function restore($id)
    {
        $phim = Phim::onlyTrashed()->findOrFail($id);
        $phim->restore();

        return redirect()->route('admin.phim.trash')
            ->with('success', 'Phim đã được khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $phim = Phim::onlyTrashed()->findOrFail($id);

        // Xóa poster nếu có
        if ($phim->poster) {
            Storage::disk('public')->delete($phim->poster);
        }

        // Xóa quan hệ với thể loại
        $phim->theLoais()->detach();

        // Xóa vĩnh viễn
        $phim->forceDelete();

        return redirect()->route('admin.phim.trash')
            ->with('success', 'Phim đã được xóa vĩnh viễn!');
    }
}
