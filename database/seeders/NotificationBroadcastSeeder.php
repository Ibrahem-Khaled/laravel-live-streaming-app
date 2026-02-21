<?php

namespace Database\Seeders;

use App\Models\NotificationBroadcast;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationBroadcastSeeder extends Seeder
{
    /**
     * تشغيل السيدر ببيانات وهمية لبثوط الإشعارات.
     */
    public function run(): void
    {
        $admin = User::query()->where('role', User::ROLE_ADMIN)->first();
        if (!$admin) {
            return;
        }

        $items = [
            [
                'user_id'          => $admin->id,
                'title'            => 'ترحيب بمنصة IPTV',
                'body'             => 'نرحب بكم في منصتنا. نتمنى لكم تجربة مشاهدة ممتعة.',
                'url'              => null,
                'audience_type'    => 'all',
                'recipients_count' => 5,
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'صيانة مجدولة',
                'body'             => 'سيتم إجراء صيانة على الخوادم يوم الجمعة من الساعة 2 صباحاً حتى 4 صباحاً.',
                'url'              => null,
                'audience_type'    => 'all',
                'recipients_count' => 5,
            ],
            [
                'user_id'          => $admin->id,
                'title'            => 'محتوى جديد',
                'body'             => 'تم إضافة مسلسلات جديدة إلى المكتبة. استمتع بالمشاهدة!',
                'url'              => '/',
                'audience_type'    => 'users',
                'recipients_count' => 3,
            ],
        ];

        foreach ($items as $data) {
            NotificationBroadcast::updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'title'   => $data['title'],
                ],
                $data
            );
        }
    }
}
