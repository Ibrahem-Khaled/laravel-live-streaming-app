<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoNotificationSeeder extends Seeder
{
    /**
     * إدراج إشعارات وهمية في جدول notifications لعرضها في واجهة المستخدم.
     */
    public function run(): void
    {
        $user = User::query()->where('role', User::ROLE_ADMIN)->first();
        if (!$user) {
            return;
        }

        $notifications = [
            [
                'title' => 'ترحيب بالمنصة',
                'body'  => 'تم تفعيل حسابك بنجاح. يمكنك الآن تصفح المحتوى.',
            ],
            [
                'title' => 'تحديث النظام',
                'body'  => 'تم تحديث المنصة بميزات جديدة وتحسينات في الأداء.',
            ],
            [
                'title' => 'رسالة جديدة',
                'body'  => 'لديك رسالة جديدة في صفحة التواصل.',
                'url'   => '/dashboard/contact_messages',
            ],
        ];

        foreach ($notifications as $item) {
            $data = array_filter([
                'title' => $item['title'],
                'body'  => $item['body'],
                'url'   => $item['url'] ?? null,
            ]);

            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => GeneralNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode($data),
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
