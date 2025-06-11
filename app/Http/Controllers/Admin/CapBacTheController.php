<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapBacThe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CapBacTheController extends Controller
{
    /**
     * Hiển thị danh sách cấp bậc thẻ
     */
    public function index()
    {
        $capBacThes = CapBacThe::orderBy('tong_chi_tieu', 'asc')->paginate(5);
        return view('admin.cap-bac-the.index', compact('capBacThes'));
    }

    /**
     * Hiển thị form tạo mới cấp bậc thẻ
     */
    public function create()
    {
        // Kiểm tra xem có cấp bậc mặc định nào không
        $hasDefault = CapBacThe::where('is_default', true)->exists();
        return view('admin.cap-bac-the.create', compact('hasDefault'));
    }

    /**
     * Lưu cấp bậc thẻ mới vào database
     */
    public function store(Request $request)
    {
        // Kiểm tra số lượng cấp bậc hiện tại
        $currentCount = CapBacThe::count();

        if ($currentCount >= 5) {
            return redirect()->route('admin.cap-bac-the.index')
                ->with('error', 'Không thể tạo thêm cấp bậc thẻ. Số lượng tối đa là 5 cấp bậc.');
        }

        $validator = Validator::make($request->all(), [
            'ten' => 'required|string|max:255|unique:cap_bac_thes',
            'mo_ta' => 'required|string|max:255',
            'tong_chi_tieu' => 'required|integer|min:0|max:10000000', // tối đa 10 triệu
            'phan_tram_ve' => 'required|integer|min:0|max:30', // tối đa 30%
            'phan_tram_dich_vu' => 'required|integer|min:0|max:30', // tối đa 30%
            'is_default' => 'boolean',
        ], [
            'ten.required' => 'Tên cấp bậc là bắt buộc.',
            'ten.string' => 'Tên cấp bậc phải là chuỗi văn bản.',
            'ten.max' => 'Tên cấp bậc không được vượt quá 255 ký tự.',
            'ten.unique' => 'Tên cấp bậc đã tồn tại.',
            'mo_ta.required' => 'Mô tả là bắt buộc.',
            'mo_ta.string' => 'Mô tả phải là chuỗi văn bản.',
            'mo_ta.max' => 'Mô tả không được vượt quá 255 ký tự.',
            'tong_chi_tieu.required' => 'Tổng chi tiêu là bắt buộc.',
            'tong_chi_tieu.integer' => 'Tổng chi tiêu phải là số nguyên.',
            'tong_chi_tieu.min' => 'Tổng chi tiêu phải lớn hơn hoặc bằng 0.',
            'tong_chi_tieu.max' => 'Tổng chi tiêu không được vượt quá 10 triệu.',
            'phan_tram_ve.required' => 'Phần trăm hoàn tiền là bắt buộc.',
            'phan_tram_ve.integer' => 'Phần trăm hoàn tiền phải là số nguyên.',
            'phan_tram_ve.min' => 'Phần trăm hoàn tiền phải lớn hơn hoặc bằng 0.',
            'phan_tram_ve.max' => 'Phần trăm hoàn tiền không được vượt quá 30%.',
            'phan_tram_dich_vu.required' => 'Phần trăm ưu đãi dịch vụ là bắt buộc.',
            'phan_tram_dich_vu.integer' => 'Phần trăm ưu đãi dịch vụ phải là số nguyên.',
            'phan_tram_dich_vu.min' => 'Phần trăm ưu đãi dịch vụ phải lớn hơn hoặc bằng 0.',
            'phan_tram_dich_vu.max' => 'Phần trăm ưu đãi dịch vụ không được vượt quá 30%.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.cap-bac-the.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Nếu đánh dấu là mặc định, hủy tất cả các mặc định khác
        if ($request->has('is_default') && $request->is_default) {
            CapBacThe::where('is_default', true)->update(['is_default' => false]);
        }

        CapBacThe::create($request->all());

        return redirect()->route('admin.cap-bac-the.index')
            ->with('success', 'Cấp bậc thẻ đã được tạo thành công.');
    }

    /**
     * Hiển thị thông tin chi tiết cấp bậc thẻ
     */
    public function show(CapBacThe $capBacThe)
    {
        return view('admin.cap-bac-the.show', compact('capBacThe'));
    }

    /**
     * Hiển thị form chỉnh sửa cấp bậc thẻ
     */
    public function edit(CapBacThe $capBacThe)
    {
        // Kiểm tra xem có cấp bậc mặc định nào không
        $hasDefault = CapBacThe::where('is_default', true)->exists();
        return view('admin.cap-bac-the.edit', compact('capBacThe', 'hasDefault'));
    }

    /**
     * Cập nhật thông tin cấp bậc thẻ
     */
    public function update(Request $request, CapBacThe $capBacThe)
    {
        $validator = Validator::make($request->all(), [
            'ten' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cap_bac_thes')->ignore($capBacThe->id),
            ],
            'mo_ta' => 'required|string|max:255',
            'tong_chi_tieu' => 'required|integer|min:0|max:10000000', // tối đa 10tr
            'phan_tram_ve' => 'required|integer|min:0|max:30', // tối đa 30%
            'phan_tram_dich_vu' => 'required|integer|min:0|max:30', // tối đa 30%
            'is_default' => 'boolean',
        ], [
            'ten.required' => 'Tên cấp bậc là bắt buộc.',
            'ten.string' => 'Tên cấp bậc phải là chuỗi văn bản.',
            'ten.max' => 'Tên cấp bậc không được vượt quá 255 ký tự.',
            'ten.unique' => 'Tên cấp bậc đã tồn tại.',
            'mo_ta.required' => 'Mô tả là bắt buộc.',
            'mo_ta.string' => 'Mô tả phải là chuỗi văn bản.',
            'mo_ta.max' => 'Mô tả không được vượt quá 255 ký tự.',
            'tong_chi_tieu.required' => 'Tổng chi tiêu là bắt buộc.',
            'tong_chi_tieu.integer' => 'Tổng chi tiêu phải là số nguyên.',
            'tong_chi_tieu.min' => 'Tổng chi tiêu phải lớn hơn hoặc bằng 0.',
            'tong_chi_tieu.max' => 'Tổng chi tiêu không được vượt quá 10 triệu.',
            'phan_tram_ve.required' => 'Phần trăm hoàn tiền là bắt buộc.',
            'phan_tram_ve.integer' => 'Phần trăm hoàn tiền phải là số nguyên.',
            'phan_tram_ve.min' => 'Phần trăm hoàn tiền phải lớn hơn hoặc bằng 0.',
            'phan_tram_ve.max' => 'Phần trăm hoàn tiền không được vượt quá 30%.',
            'phan_tram_dich_vu.required' => 'Phần trăm ưu đãi dịch vụ là bắt buộc.',
            'phan_tram_dich_vu.integer' => 'Phần trăm ưu đãi dịch vụ phải là số nguyên.',
            'phan_tram_dich_vu.min' => 'Phần trăm ưu đãi dịch vụ phải lớn hơn hoặc bằng 0.',
            'phan_tram_dich_vu.max' => 'Phần trăm ưu đãi dịch vụ không được vượt quá 30%.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.cap-bac-the.edit', $capBacThe->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Nếu đánh dấu là mặc định, hủy tất cả các mặc định khác
        if ($request->has('is_default') && $request->is_default) {
            CapBacThe::where('id', '!=', $capBacThe->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $capBacThe->update($request->all());

        return redirect()->route('admin.cap-bac-the.index')
            ->with('success', 'Cấp bậc thẻ đã được cập nhật thành công.');
    }

    /**
     * Xóa cấp bậc thẻ
     */
    public function destroy(CapBacThe $capBacThe)
    {
        // Không cho phép xóa cấp bậc mặc định
        if ($capBacThe->is_default) {
            return redirect()->route('admin.cap-bac-the.index')
                ->with('error', 'Không thể xóa cấp bậc thẻ mặc định.');
        }

        // Kiểm tra số lượng cấp bậc thẻ còn lại
        if (CapBacThe::count() <= 2) {
            return redirect()->route('admin.cap-bac-the.index')
                ->with('error', 'Phải có ít nhất 2 cấp bậc thẻ trong hệ thống.');
        }

        $capBacThe->delete();

        return redirect()->route('admin.cap-bac-the.index')
            ->with('success', 'Cấp bậc thẻ đã được xóa thành công.');
    }

    /**
     * Đặt cấp bậc thẻ làm mặc định
     */
    public function setDefault(CapBacThe $capBacThe)
    {
        // Kiểm tra xem đã có cấp bậc mặc định chưa
        $hasDefault = CapBacThe::where('is_default', true)->exists();

        if ($hasDefault) {
            return redirect()->route('admin.cap-bac-the.index')
                ->with('error', 'Đã có cấp bậc mặc định. Không thể đặt thêm cấp bậc mặc định khác.');
        }

        // Hủy tất cả các mặc định khác
        CapBacThe::where('is_default', true)->update(['is_default' => false]);

        // Đặt cấp bậc hiện tại làm mặc định
        $capBacThe->update(['is_default' => true]);

        return redirect()->route('admin.cap-bac-the.index')
            ->with('success', 'Cấp bậc thẻ đã được đặt làm mặc định.');
    }
}
