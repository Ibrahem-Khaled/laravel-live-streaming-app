<?php

namespace App\Http\Requests;

use App\Models\Content;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:contents,slug'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(array_keys(Content::TYPES))],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'duration' => ['nullable', 'integer', 'min:1'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'quality' => ['nullable', Rule::in(array_keys(Content::QUALITIES))],
            'language' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:50'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];

        // الصور: إما رفع ملف أو URL
        $rules['image'] = ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'];
        $rules['image_url'] = ['nullable', 'url', 'max:500'];
        $rules['poster'] = ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'];
        $rules['poster_url'] = ['nullable', 'url', 'max:500'];
        $rules['banner'] = ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'];
        $rules['banner_url'] = ['nullable', 'url', 'max:500'];

        // رابط البث: إما ملف فيديو أو URL
        if ($this->type === Content::TYPE_SERIES) {
            // للمسلسلات، stream_url اختياري (الحلقات لها روابطها)
            $rules['stream_url'] = ['nullable', 'url', 'max:1000'];
            $rules['stream_file'] = ['nullable', 'file', 'mimes:mp4,mkv,webm,m3u8,avi,mov,flv', 'max:2147483648']; // 2GB
        } else {
            // للأفلام والقنوات والبث المباشر: مطلوب إما رابط أو ملف
            $rules['stream_url'] = ['nullable', 'url', 'max:1000'];
            $rules['stream_file'] = ['nullable', 'file', 'mimes:mp4,mkv,webm,m3u8,avi,mov,flv', 'max:2147483648'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'slug' => 'الرابط',
            'description' => 'الوصف',
            'type' => 'النوع',
            'category_id' => 'الفئة',
            'image' => 'الصورة',
            'image_url' => 'رابط الصورة',
            'poster' => 'البوستر',
            'poster_url' => 'رابط البوستر',
            'banner' => 'البانر',
            'banner_url' => 'رابط البانر',
            'stream_url' => 'رابط البث',
            'stream_file' => 'ملف البث',
            'sort_order' => 'ترتيب العرض',
            'is_active' => 'الحالة',
            'is_featured' => 'مميز',
            'year' => 'سنة الإنتاج',
            'duration' => 'المدة',
            'rating' => 'التقييم',
            'quality' => 'الجودة',
            'language' => 'اللغة',
            'country' => 'الدولة',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // التحقق من وجود إما رابط أو ملف للبث (للأنواع غير المسلسلات)
            if ($this->type !== Content::TYPE_SERIES) {
                if (blank($this->stream_url) && !$this->hasFile('stream_file')) {
                    $validator->errors()->add('stream_url', 'يجب إدخال رابط البث أو رفع ملف.');
                }
            }

            // التحقق من عدم وجود image و image_url معاً
            if ($this->hasFile('image') && filled($this->image_url)) {
                $validator->errors()->add('image', 'يجب اختيار إما رفع صورة أو إدخال رابط.');
            }

            if ($this->hasFile('poster') && filled($this->poster_url)) {
                $validator->errors()->add('poster', 'يجب اختيار إما رفع بوستر أو إدخال رابط.');
            }

            if ($this->hasFile('banner') && filled($this->banner_url)) {
                $validator->errors()->add('banner', 'يجب اختيار إما رفع بانر أو إدخال رابط.');
            }
        });
    }
}
