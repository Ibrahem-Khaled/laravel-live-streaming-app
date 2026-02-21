<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين مع فلترة وبحث.
     */
    public function index(Request $request): View
    {
        $users = User::query()
            ->search($request->input('q'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'   => User::count(),
            'active'  => User::active()->count(),
            'inactive'=> User::inactive()->count(),
            'blocked' => User::blocked()->count(),
        ];

        return view('dashboard.users.index', compact('users', 'stats'));
    }

    /**
     * نموذج إضافة مستخدم جديد.
     */
    public function create(): View
    {
        return view('dashboard.users.create');
    }

    /**
     * حفظ مستخدم جديد.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * عرض تفاصيل مستخدم (اختياري).
     */
    public function show(User $user): View
    {
        return view('dashboard.users.show', compact('user'));
    }

    /**
     * نموذج تعديل مستخدم.
     */
    public function edit(User $user): View
    {
        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }
        if (blank($data['username'] ?? null)) {
            unset($data['username']);
        }

        $user->update($data);

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    /**
     * حذف مستخدم.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * تغيير حالة المستخدم (تفعيل / إلغاء تفعيل / حظر).
     */
    public function status(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:active,inactive,blocked'],
        ]);

        $user->update(['status' => $request->status]);

        $messages = [
            'active'   => 'تم تفعيل المستخدم بنجاح.',
            'inactive' => 'تم إلغاء تفعيل المستخدم.',
            'blocked'  => 'تم حظر المستخدم.',
        ];

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', $messages[$request->status]);
    }
}
