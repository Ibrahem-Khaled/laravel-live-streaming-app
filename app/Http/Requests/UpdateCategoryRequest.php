<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:categories,id', function ($attribute, $value, $fail) use ($categoryId) {
                if ($value == $categoryId) {
                    $fail('لا يمكن أن تكون الفئة فرعية لنفسها.');
                }
            }],
            'content_type' => ['nullable', Rule::in(array_keys(Category::CONTENT_TYPES))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'slug' => 'الرابط',
            'description' => 'الوصف',
            'image' => 'الصورة',
            'image_url' => 'رابط الصورة',
            'icon' => 'الأيقونة',
            'parent_id' => 'الفئة الرئيسية',
            'content_type' => 'نوع المحتوى',
            'sort_order' => 'ترتيب العرض',
            'is_active' => 'الحالة',
            'is_featured' => 'مميز',
            'meta_title' => 'عنوان SEO',
            'meta_description' => 'وصف SEO',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // إذا كان image_url موجود، لا نحتاج image
        if ($this->has('image_url') && filled($this->image_url)) {
            $this->merge(['image' => null]);
        }
    }
}
