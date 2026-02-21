<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'     => ['nullable', 'string', 'max:255'],
            'subtitle'  => ['nullable', 'string', 'max:255'],
            'image'     => ['nullable', 'image', 'max:5120'],
            'image_url' => ['nullable', 'string', 'url'],
            'link'      => ['nullable', 'string', 'max:500'],
            'order'     => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at'   => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'     => 'العنوان',
            'subtitle'  => 'العنوان الفرعي',
            'image'     => 'الصورة',
            'link'      => 'الرابط',
            'order'     => 'الترتيب',
            'is_active' => 'نشط',
            'starts_at' => 'بداية العرض',
            'ends_at'   => 'نهاية العرض',
        ];
    }
}
