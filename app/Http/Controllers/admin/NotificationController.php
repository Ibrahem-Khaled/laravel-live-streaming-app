<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationBroadcast;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Services\UserSearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * قائمة الإشعارات المرسلة.
     */
    public function index(): View
    {
        $broadcasts = NotificationBroadcast::query()
            ->where('user_id', request()->user()->id)
            ->with('sender')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.notifications.index', compact('broadcasts'));
    }

    /**
     * صفحة إرسال إشعار جديد.
     */
    public function create(UserSearchService $userSearchService): View
    {
        $preselectedUsers = collect();
        $preselectedIds = old('user_ids', []);
        if (!empty($preselectedIds)) {
            $preselectedUsers = $userSearchService->getByIds((array) $preselectedIds);
        }

        return view('dashboard.notifications.create', compact('preselectedUsers'));
    }

    /**
     * إرسال إشعار للجمهور المحدد.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string', 'max:5000'],
            'url'          => ['nullable', 'string', 'max:500'],
            'audience'     => ['required', 'string', 'in:all,admins,users,roles,selected'],
            'roles'        => ['required_if:audience,roles', 'array'],
            'roles.*'      => ['string', 'in:admin,user,tester'],
            'user_ids'     => ['nullable', 'array'],
            'user_ids.*'   => ['integer', 'exists:users,id'],
        ]);

        $query = User::query()->active();

        switch ($request->audience) {
            case 'all':
                break;
            case 'admins':
                $query->byRole(User::ROLE_ADMIN);
                break;
            case 'users':
                $query->byRole(User::ROLE_USER);
                break;
            case 'roles':
                $query->whereIn('role', $request->roles ?? []);
                break;
            case 'selected':
                $ids = array_filter(array_map('intval', (array) ($request->user_ids ?? [])));
                if (empty($ids)) {
                    return redirect()->back()->withInput()->withErrors(['audience' => 'يجب اختيار مستلمين على الأقل عند اختيار "مستخدمون محددون".']);
                }
                $query->whereIn('id', $ids);
                break;
        }

        $recipients = $query->get();
        $count = $recipients->count();

        $broadcast = NotificationBroadcast::create([
            'user_id'           => $request->user()->id,
            'title'             => $request->title,
            'body'              => $request->body,
            'url'               => $request->url,
            'audience_type'     => $request->audience,
            'recipients_count'  => $count,
        ]);

        $notification = new GeneralNotification(
            $request->title,
            $request->body,
            $request->url,
            $request->user()->id,
            $broadcast->id
        );

        foreach ($recipients as $user) {
            $user->notify($notification);
        }

        return redirect()
            ->route('dashboard.notifications.index')
            ->with('success', "تم إرسال الإشعار إلى {$count} مستلم.");
    }

    /**
     * عرض تفاصيل إشعار مرسل ومن شافه.
     */
    public function show(NotificationBroadcast $broadcast): View
    {
        $this->authorizeBroadcast($broadcast);
        $broadcast->load('sender');
        $recipients = $broadcast->getRecipientsWithReadStatus();

        return view('dashboard.notifications.show', compact('broadcast', 'recipients'));
    }

    /**
     * نموذج تعديل إشعار مرسل (العنوان والنص والرابط للعرض فقط).
     */
    public function edit(NotificationBroadcast $broadcast): View
    {
        $this->authorizeBroadcast($broadcast);

        return view('dashboard.notifications.edit', compact('broadcast'));
    }

    /**
     * تحديث إشعار مرسل.
     */
    public function update(Request $request, NotificationBroadcast $broadcast): RedirectResponse
    {
        $this->authorizeBroadcast($broadcast);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['nullable', 'string', 'max:5000'],
            'url'   => ['nullable', 'string', 'max:500'],
        ]);

        $broadcast->update($request->only('title', 'body', 'url'));

        return redirect()
            ->route('dashboard.notifications.broadcasts.show', $broadcast)
            ->with('success', 'تم تحديث الإشعار.');
    }

    /**
     * حذف إشعار مرسل (والسجلات المرتبطة في صندوق المستلمين).
     */
    public function destroy(NotificationBroadcast $broadcast): RedirectResponse
    {
        $this->authorizeBroadcast($broadcast);

        $broadcast->deleteWithRelatedNotifications();

        return redirect()
            ->route('dashboard.notifications.index')
            ->with('success', 'تم حذف الإشعار.');
    }

    private function authorizeBroadcast(NotificationBroadcast $broadcast): void
    {
        if ($broadcast->user_id !== request()->user()->id) {
            abort(403, 'غير مصرح.');
        }
    }
}
