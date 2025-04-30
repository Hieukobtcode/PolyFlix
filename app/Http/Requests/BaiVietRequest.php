<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaiVietRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi yêu cầu này hay không.
     *
     * @return bool
     */
    public function authorize()
    {
        // Trả về true nếu tất cả người dùng đều có quyền gửi yêu cầu này, nếu không thì trả về false
        return true;
    }

    /**
     * Lấy các quy tắc xác thực cho yêu cầu.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'hinh_anh' => 'nullable|mimes:jpeg,jpg,png|max:2048',  // Hình ảnh với định dạng và dung lượng hạn chế
            'status' => 'required|in:draft,published', // Trạng thái bài viết
        ];
    }

    /**
     * Các thông báo xác thực.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'tieu_de.required' => 'Tiêu đề là trường bắt buộc.',
            'tieu_de.string' => 'Tiêu đề phải là một chuỗi.',
            'tieu_de.max' => 'Tiêu đề không được dài quá 255 ký tự.',
            'tieu_de.unique' => 'Tiêu đề đã tồn tại.',
            'noi_dung.required' => 'Nội dung bài viết là trường bắt buộc.',
            'noi_dung.string' => 'Nội dung bài viết phải là một chuỗi.',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng: jpeg, jpg, png.',
            'hinh_anh.max' => 'Hình ảnh không được lớn hơn 2MB.',
            'status.required' => 'Trạng thái bài viết là trường bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ, chỉ chấp nhận các giá trị: draft, published.',
        ];
    }

    /**
     * Các thuộc tính của các trường dữ liệu.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'tieu_de' => 'Tiêu đề',
            'noi_dung' => 'Nội dung',
            'hinh_anh' => 'Hình ảnh',
            'status' => 'Trạng thái',
        ];
    }
}
