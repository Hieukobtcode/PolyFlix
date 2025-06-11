<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoDoGheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
            'ghe_thuong' => 'required|min:1',
            'ghe_vip' => 'required|min:1',
            'ghe_doi' => 'required|min:1',
        ];
    }

    public function messages()
    {
        return [
            'mau_so_do.required' => 'Hãy chọn mẫu sơ đồ ghế',
            'ghe_thuong.required' => 'Hãy nhập số hàng ghế thường',
            'ghe_vip.required' => 'Hãy nhập số hàng ghế vip',
            'ghe_doi.required' => 'Hãy nhập số hàng ghế đôi',
            'ghe_thuong.min' => 'Số hàng ghế thường phải lớn hơn hoặc bằng 1',
            'ghe_vip.min' => 'Số hàng ghế VIP phải lớn hơn hoặc bằng 1',
            'ghe_doi.min' => 'Số hàng ghế đôi phải lớn hơn hoặc bằng 1',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $soHang = (int) $this->input('so_hang', 0);  
            $gheThuong = (int) $this->input('ghe_thuong', 0);
            $gheVip = (int) $this->input('ghe_vip', 0);
            $gheDoi = (int) $this->input('ghe_doi', 0);

            $total = $gheThuong + $gheVip + $gheDoi;

            if ($total > $soHang) {
                $validator->errors()->add('tong_ghe', "Tổng số hàng loại ghế ({$gheThuong} thường + {$gheVip} VIP + {$gheDoi} đôi) vượt quá tổng số hàng ({$soHang}).");
            }
        });
    }
}
