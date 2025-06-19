<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
            'dob'                   => 'nullable|date',
            'phone'                 => 'nullable|string|max:20',
            'username'              => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Vui lòng nhập họ và tên.',
            'email.required'        => 'Vui lòng nhập email.',
            'email.email'           => 'Email không đúng định dạng.',
            'email.unique'          => 'Email đã được sử dụng.',
            'password.required'     => 'Vui lòng nhập mật khẩu.',
            'password.min'          => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.confirmed'    => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
