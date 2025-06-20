<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $email = $this->input('email');
            $password = $this->input('password');

            if (empty($email) || empty($password)) {
                $v->errors()->forget('email');
                $v->errors()->forget('password');

                $v->errors()->add('form', 'Vui lòng nhập đầy đủ thông tin.');
            }
        });
    }
}