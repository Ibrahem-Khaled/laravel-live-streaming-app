<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * تشغيل السيدر ببيانات وهمية للمستخدمين.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            [
                'name'     => 'مدير النظام',
                'email'    => 'admin@iptv.local',
                'username' => 'admin',
                'phone'    => '01000000001',
                'role'     => User::ROLE_ADMIN,
                'status'   => User::STATUS_ACTIVE,
                'password' => $password,
            ],
            [
                'name'     => 'أحمد محمد',
                'email'    => 'ahmed@example.com',
                'username' => 'ahmed',
                'phone'    => '01012345678',
                'role'     => User::ROLE_USER,
                'status'   => User::STATUS_ACTIVE,
                'password' => $password,
            ],
            [
                'name'     => 'فاطمة علي',
                'email'    => 'fatma@example.com',
                'username' => 'fatma',
                'phone'    => '01198765432',
                'role'     => User::ROLE_USER,
                'status'   => User::STATUS_ACTIVE,
                'password' => $password,
            ],
            [
                'name'     => 'مختبر التطبيق',
                'email'    => 'tester@example.com',
                'username' => 'tester',
                'phone'    => null,
                'role'     => User::ROLE_TESTER,
                'status'   => User::STATUS_ACTIVE,
                'password' => $password,
            ],
            [
                'name'     => 'مستخدم غير نشط',
                'email'    => 'inactive@example.com',
                'username' => 'inactive_user',
                'phone'    => null,
                'role'     => User::ROLE_USER,
                'status'   => User::STATUS_INACTIVE,
                'password' => $password,
            ],
            [
                'name'     => 'خالد إبراهيم',
                'email'    => 'khaled@example.com',
                'username' => 'khaled',
                'phone'    => '01255555555',
                'role'     => User::ROLE_USER,
                'status'   => User::STATUS_ACTIVE,
                'password' => $password,
            ],
            [
                'name'     => 'سارة حسن',
                'email'    => 'sara@example.com',
                'username' => 'sara',
                'phone'    => '01511111111',
                'role'     => User::ROLE_USER,
                'status'   => User::STATUS_ACTIVE,
                'password' => $password,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
