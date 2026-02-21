<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * تشغيل السيدر ببيانات وهمية للسلايدر.
     */
    public function run(): void
    {
        $items = [
            [
                'title'     => 'ترحيب بمنصة IPTV',
                'subtitle'  => 'شاهد أفضل المحتويات والمسلسلات',
                'image'     => null,
                'link'      => null,
                'order'     => 1,
                'is_active' => true,
            ],
            [
                'title'     => 'عروض حصرية',
                'subtitle'  => 'محتوى جديد كل أسبوع',
                'image'     => null,
                'link'      => null,
                'order'     => 2,
                'is_active' => true,
            ],
            [
                'title'     => 'مسلسلات رمضان',
                'subtitle'  => 'أحدث الحلقات متاحة الآن',
                'image'     => null,
                'link'      => null,
                'order'     => 3,
                'is_active' => true,
            ],
        ];

        foreach ($items as $data) {
            Slider::updateOrCreate(
                ['title' => $data['title'], 'order' => $data['order']],
                $data
            );
        }
    }
}
