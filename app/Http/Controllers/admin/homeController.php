<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Content;
use App\Models\Episode;
use App\Models\NotificationBroadcast;
use App\Models\Slider;
use App\Models\User;
use Illuminate\View\View;

class homeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للوحة التحكم مع إحصائيات شاملة.
     */
    public function index(): View
    {
        $stats = [
            'users' => [
                'total'   => User::count(),
                'active'  => User::active()->count(),
                'inactive'=> User::inactive()->count(),
                'blocked' => User::blocked()->count(),
            ],
            'categories' => [
                'total'   => Category::count(),
                'active'  => Category::active()->count(),
                'featured'=> Category::featured()->count(),
            ],
            'contents' => [
                'total'   => Content::count(),
                'channels'=> Content::channels()->count(),
                'movies'  => Content::movies()->count(),
                'series'  => Content::series()->count(),
                'live'    => Content::live()->count(),
                'active'  => Content::active()->count(),
                'featured'=> Content::featured()->count(),
            ],
            'episodes' => [
                'total' => Episode::count(),
            ],
            'sliders' => [
                'total'  => Slider::count(),
                'active' => Slider::active()->count(),
            ],
            'contact_messages' => [
                'total'  => ContactMessage::count(),
                'new'    => ContactMessage::new()->count(),
                'replied'=> ContactMessage::status(ContactMessage::STATUS_REPLIED)->count(),
            ],
            'broadcasts' => [
                'total' => NotificationBroadcast::count(),
            ],
        ];

        $chartContentByType = [
            'labels' => ['قنوات', 'أفلام', 'مسلسلات', 'بث مباشر'],
            'data'   => [
                $stats['contents']['channels'],
                $stats['contents']['movies'],
                $stats['contents']['series'],
                $stats['contents']['live'],
            ],
        ];

        $recentUsers = User::query()->latest()->take(5)->get();
        $recentContactMessages = ContactMessage::query()->latest()->take(5)->get();

        return view('dashboard.home', compact('stats', 'chartContentByType', 'recentUsers', 'recentContactMessages'));
    }
}
