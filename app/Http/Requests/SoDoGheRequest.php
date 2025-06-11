<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoDoGheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  // Chấp nhận yêu cầu từ tất cả người dùng
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mau_so_do' => 'required|in:8x12,10x12,12x14,14x16,18x20',
            'loai_ghe_ids' => 'required|array',
            'loai_ghe_ids.*' => 'integer|exists:loai_ghes,id',
        ];
    }

    public function messages()
    {
        return [
            'mau_so_do.required' => 'Hãy chọn mẫu sơ đồ ghế',
            'loai_ghe_ids.required' => 'Hãy chọn loại ghế',
        ];
    }

    /**
     * Xử lý sau khi xác thực dữ liệu.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
        // protected function withValidator($validator)
        // {
        //     $validator->after(function ($validator) {
        //         $hangGheTheoLoais = $this->loai_ghe_ids;  
        //         $soHangGhe = (int) $this->input('so_hang'); 
        //         $tongSoHangGhe = 0;  

        //         foreach ($hangGheTheoLoais as $hangGheTheoLoai) {
        //             $loaiGhe = 'so_hang_' . (int) $hangGheTheoLoai;  
        //             $ghe = $this->input($loaiGhe); 

        //             if (empty($ghe)) {
        //                 $validator->errors()->add('loi_loai_ghe_' . $hangGheTheoLoai, 'Không được để trống');
        //             }
        //             elseif ((int)$ghe === 0) {
        //                 $validator->errors()->add('loi_loai_ghe_' . $hangGheTheoLoai, 'Số hàng ghế phải lớn hơn 0');
        //             }

        //             $tongSoHangGhe += (int) $ghe;
        //         }

        //         if ($tongSoHangGhe > $soHangGhe) {
        //             $validator->errors()->add('tong_hang_ghe', 'Tổng số hàng không được vượt quá ' . $soHangGhe);
        //         }
        //     });
        // }
}
