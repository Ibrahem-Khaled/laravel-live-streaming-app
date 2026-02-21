<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $episodeId = $this->route('episode')->id;

        return [
            'season_number' => ['required', 'integer', 'min:1'],
            'episode_number' => ['required', 'integer', 'min:1'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'stream_url' => ['nullable', 'url', 'max:1000'],
            'stream_file' => ['nullable', 'file', 'mimes:mp4,mkv,webm,m3u8,avi,mov,flv', 'max:2147483648'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'aired_at' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'season_number' => 'رقم الموسم',
            'episode_number' => 'رقم الحلقة',
            'title' => 'العنوان',
            'description' => 'الوصف',
            'image' => 'الصورة',
            'image_url' => 'رابط الصورة',
            'stream_url' => 'رابط البث',
            'stream_file' => 'ملف البث',
            'duration' => 'المدة',
            'sort_order' => 'ترتيب العرض',
            'aired_at' => 'تاريخ البث',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $episode = $this->route('episode');
            $contentId = $episode->content_id;
            $seasonNumber = $this->input('season_number');
            $episodeNumber = $this->input('episode_number');

            // التحقق من عدم تكرار الموسم والحلقة لنفس المسلسل (ما عدا الحلقة الحالية)
            if ($contentId && $seasonNumber && $episodeNumber) {
                $exists = \App\Models\Episode::where('content_id', $contentId)
                    ->where('season_number', $seasonNumber)
                    ->where('episode_number', $episodeNumber)
                    ->where('id', '!=', $episode->id)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('episode_number', 'هذه الحلقة موجودة بالفعل في هذا الموسم.');
                }
            }

            // التحقق من عدم وجود stream_url و stream_file معاً
            if (filled($this->stream_url) && $this->hasFile('stream_file')) {
                $validator->errors()->add('stream_url', 'يجب اختيار إما رابط أو رفع ملف.');
            }

            // التحقق من عدم وجود image و image_url معاً
            if ($this->hasFile('image') && filled($this->image_url)) {
                $validator->errors()->add('image', 'يجب اختيار إما رفع صورة أو إدخال رابط.');
            }
        });
    }
}
