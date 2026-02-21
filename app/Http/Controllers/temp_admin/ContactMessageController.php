<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'   => ContactMessage::count(),
            'new'     => ContactMessage::new()->count(),
            'replied' => ContactMessage::status(ContactMessage::STATUS_REPLIED)->count(),
        ];

        return view('dashboard.contact_messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $contact_message): View
    {
        $contact_message->load('user');
        if ($contact_message->status === ContactMessage::STATUS_NEW) {
            $contact_message->update(['status' => ContactMessage::STATUS_READ]);
        }
        return view('dashboard.contact_messages.show', compact('contact_message'));
    }

    public function update(Request $request, ContactMessage $contact_message): RedirectResponse
    {
        $request->validate([
            'status'      => ['sometimes', 'string', 'in:new,read,replied,closed'],
            'admin_reply' => ['nullable', 'string', 'max:5000'],
        ]);

        $data = [];
        if ($request->has('status')) {
            $data['status'] = $request->status;
        }
        if ($request->has('admin_reply')) {
            $data['admin_reply'] = $request->admin_reply;
            $data['replied_at'] = now();
            $data['status'] = ContactMessage::STATUS_REPLIED;
        }

        $contact_message->update($data);

        $msg = isset($data['admin_reply']) ? 'تم إرسال الرد بنجاح.' : 'تم تحديث الحالة.';

        return redirect()
            ->route('dashboard.contact_messages.show', $contact_message)
            ->with('success', $msg);
    }
}
