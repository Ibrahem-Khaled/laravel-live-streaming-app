<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserNotificationController extends Controller
{
    /**
     * عرض إشعارات المستخدم الحالي.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $notifications = $user->notifications()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * تعليم إشعار كمقروء.
     */
    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'تم تعليم الإشعار كمقروء.');
    }

    /**
     * تعليم الكل كمقروء.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة.');
    }
}
