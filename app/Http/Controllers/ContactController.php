<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * عرض نموذج التواصل (للزائر والمسجل).
     */
    public function create(): View
    {
        return view('contact.create');
    }

    /**
     * حفظ رسالة التواصل.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $data = $request->only('name', 'email', 'subject', 'message');
        if ($request->user()) {
            $data['user_id'] = $request->user()->id;
            $data['name'] = $data['name'] ?: $request->user()->name;
            $data['email'] = $data['email'] ?: $request->user()->email;
        } else {
            $data['user_id'] = null;
        }

        ContactMessage::create($data);

        return redirect()
            ->route('contact.create')
            ->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
    }
}
