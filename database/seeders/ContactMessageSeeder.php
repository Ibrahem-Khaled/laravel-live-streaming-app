<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * تشغيل السيدر ببيانات وهمية لرسائل التواصل.
     */
    public function run(): void
    {
        $user = User::query()->where('role', User::ROLE_USER)->first();

        $items = [
            [
                'name'         => 'محمد أحمد',
                'email'        => 'mohamed@example.com',
                'subject'      => 'استفسار عن الاشتراك',
                'message'      => 'أود معرفة خطط الاشتراك والأسعار المتاحة للمنصة.',
                'user_id'      => $user?->id,
                'status'       => ContactMessage::STATUS_NEW,
                'admin_reply'  => null,
                'replied_at'   => null,
            ],
            [
                'name'         => 'نورة سعيد',
                'email'        => 'nora@example.com',
                'subject'      => 'مشكلة في التشغيل',
                'message'      => 'عند فتح بعض الحلقات لا يعمل الفيديو بشكل صحيح، هل يمكن المساعدة؟',
                'user_id'      => null,
                'status'       => ContactMessage::STATUS_READ,
                'admin_reply'  => null,
                'replied_at'   => null,
            ],
            [
                'name'         => 'يوسف حسن',
                'email'        => 'youssef@example.com',
                'subject'      => 'اقتراح محتوى',
                'message'      => 'نتمنى إضافة المزيد من المسلسلات التاريخية والوثائقية.',
                'user_id'      => null,
                'status'       => ContactMessage::STATUS_REPLIED,
                'admin_reply'  => 'شكراً لاقتراحكم، سنأخذ ذلك بعين الاعتبار في خطة المحتوى القادمة.',
                'replied_at'   => now()->subDays(2),
            ],
            [
                'name'         => 'هدى علي',
                'email'        => 'huda@example.com',
                'subject'      => 'تسجيل الدخول',
                'message'      => 'نسيت كلمة المرور ولا أستطيع استعادتها عبر البريد.',
                'user_id'      => null,
                'status'       => ContactMessage::STATUS_CLOSED,
                'admin_reply'  => 'تم حل المشكلة عبر إرسال رابط إعادة تعيين كلمة المرور.',
                'replied_at'   => now()->subDays(5),
            ],
        ];

        foreach ($items as $data) {
            ContactMessage::updateOrCreate(
                ['email' => $data['email'], 'subject' => $data['subject']],
                $data
            );
        }
    }
}
