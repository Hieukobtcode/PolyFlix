<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SoDoGheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_so_do'      => 'required|string|max:255',
            'so_hang'        => 'required',
            'so_hang_thuong' => 'required',
            'so_hang_vip'    => 'required',
            'so_hang_doi'    => 'required',
            'mo_ta'          => 'nullable',
            'trang_thai'     => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_so_do.required' => 'Vui lòng nhập tên sơ đồ.',
            'ten_so_do.max'      => 'Tên sơ đồ không được vượt quá 255 ký tự.',

            'so_hang.required' => 'Chọn ma trận ghế cho sơ đồ này',

            'so_hang_thuong.required' => 'Hãy chọn số hàng ghế.',

        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $so_hang = (int) $this->input('so_hang');
            $thuong = (int) $this->input('so_hang_thuong', 0);
            $vip    = (int) $this->input('so_hang_vip', 0);
            $doi    = (int) $this->input('so_hang_doi', 0);

            if ($thuong + $vip + $doi > $so_hang) {
                $validator->errors()->add(
                    'so_hang_loai',
                    "Tổng số hàng loại ghế ({$thuong} thường + {$vip} VIP + {$doi} đôi) vượt quá tổng số hàng ({$so_hang})."
                );
            }
        });
    }
}
