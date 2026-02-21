<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoUploadService
{
    /**
     * امتدادات الفيديو المدعومة
     */
    public const ALLOWED_EXTENSIONS = ['mp4', 'mkv', 'webm', 'm3u8', 'avi', 'mov', 'flv'];

    /**
     * رفع فيديو أو التحقق من URL
     *
     * @param UploadedFile|string|null $fileOrUrl
     * @param string $folder
     * @param string|null $oldPath
     * @return string|null
     */
    public function handle($fileOrUrl, string $folder = 'videos', ?string $oldPath = null): ?string
    {
        // إذا كان null أو فارغ
        if (blank($fileOrUrl)) {
            return $oldPath;
        }

        // إذا كان URL
        if (is_string($fileOrUrl)) {
            // التحقق من أن الرابط صحيح
            if (filter_var($fileOrUrl, FILTER_VALIDATE_URL)) {
                // حذف الملف القديم إذا كان موجوداً
                if ($oldPath && !filter_var($oldPath, FILTER_VALIDATE_URL)) {
                    $this->delete($oldPath);
                }
                return $fileOrUrl;
            }
            // إذا كان مسار نسبي موجود
            if ($fileOrUrl && Storage::disk('public')->exists($fileOrUrl)) {
                return $fileOrUrl;
            }
            return $oldPath;
        }

        // إذا كان ملف مرفوع
        if ($fileOrUrl instanceof UploadedFile) {
            // التحقق من نوع الملف
            if (!$fileOrUrl->isValid()) {
                throw new \InvalidArgumentException('الملف المرفوع غير صالح.');
            }

            $extension = strtolower($fileOrUrl->getClientOriginalExtension());
            if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
                throw new \InvalidArgumentException('نوع الملف غير مدعوم. الأنواع المدعومة: ' . implode(', ', self::ALLOWED_EXTENSIONS));
            }

            // التحقق من حجم الملف (اختياري - يمكن إزالته)
            $maxSize = 1024 * 1024 * 1024 * 2; // 2GB
            if ($fileOrUrl->getSize() > $maxSize) {
                throw new \InvalidArgumentException('حجم الملف كبير جداً. الحد الأقصى: 2GB');
            }

            // حذف الملف القديم
            if ($oldPath && !filter_var($oldPath, FILTER_VALIDATE_URL)) {
                $this->delete($oldPath);
            }

            // إنشاء اسم فريد
            $filename = $this->generateUniqueFilename($fileOrUrl, $folder);

            // رفع الملف
            $path = $fileOrUrl->storeAs($folder, $filename, 'public');

            return $path;
        }

        return $oldPath;
    }

    /**
     * حذف فيديو
     *
     * @param string|null $path
     * @return bool
     */
    public function delete(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        // إذا كان URL خارجي، لا نحذفه
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return false;
        }

        // حذف من التخزين المحلي
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * إنشاء اسم فريد للملف
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file, string $folder): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $basename = Str::slug($basename);
        $filename = $basename . '-' . time() . '-' . Str::random(8) . '.' . $extension;

        // التأكد من عدم التكرار
        $counter = 1;
        while (Storage::disk('public')->exists($folder . '/' . $filename)) {
            $filename = $basename . '-' . time() . '-' . Str::random(8) . '-' . $counter . '.' . $extension;
            $counter++;
        }

        return $filename;
    }

    /**
     * الحصول على URL للفيديو
     *
     * @param string|null $path
     * @return string|null
     */
    public function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        // إذا كان URL خارجي
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // URL محلي
        return Storage::disk('public')->url($path);
    }

    /**
     * التحقق من أن القيمة إما ملف أو رابط
     *
     * @param mixed $value
     * @return bool
     */
    public static function validateFileOrUrl($value): bool
    {
        if (blank($value)) {
            return false;
        }

        // إذا كان ملف
        if ($value instanceof UploadedFile) {
            return $value->isValid();
        }

        // إذا كان رابط
        if (is_string($value)) {
            return filter_var($value, FILTER_VALIDATE_URL) !== false;
        }

        return false;
    }
}
