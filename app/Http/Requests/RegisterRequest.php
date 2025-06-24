<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'dob'      => 'nullable|date',
            'phone'    => 'nullable|string|max:20|unique:users,so_dien_thoai',
            'g-recaptcha-response'  => 'required|captcha',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Vui lòng nhập họ và tên.',
            'email.required'         => 'Vui lòng nhập email.',
            'email.email'            => 'Email không đúng định dạng.',
            'email.unique'           => 'Email đã được sử dụng.',
            'password.required'      => 'Vui lòng nhập mật khẩu.',
            'password.min'           => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.confirmed'     => 'Xác nhận mật khẩu không khớp.',
            'phone.unique'           => 'Số điện thoại đã được sử dụng.',
            'username.unique'        => 'Tên đăng nhập đã tồn tại.',
            'g-recaptcha-response.required' => 'Vui lòng xác minh bạn không phải người máy.',
            'g-recaptcha-response.captcha'  => 'CAPTCHA không hợp lệ hoặc đã hết hạn.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $name     = $this->input('name');
            $email    = $this->input('email');
            $password = $this->input('password');
            $confirm  = $this->input('password_confirmation');

            if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
                $v->errors()->forget('name');
                $v->errors()->forget('email');
                $v->errors()->forget('password');
                $v->errors()->forget('password_confirmation');

                $v->errors()->add('form', 'Vui lòng nhập đầy đủ thông tin.');
            }
        });
    }
}