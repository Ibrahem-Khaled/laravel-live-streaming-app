<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($userId), 'regex:/^[a-zA-Z0-9_\-.]+$/'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
            'status'   => ['required', Rule::in(array_keys(User::STATUSES))],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'الاسم',
            'email'    => 'البريد الإلكتروني',
            'username' => 'اسم المستخدم',
            'phone'    => 'رقم الجوال',
            'role'     => 'الدور',
            'status'   => 'الحالة',
            'password' => 'كلمة المرور',
        ];
    }
}
