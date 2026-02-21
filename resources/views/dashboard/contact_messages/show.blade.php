@extends('layouts.dashboard.app')

@section('title', 'عرض الرسالة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">رسالة تواصل</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.contact_messages.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>{{ $contact_message->subject }}</strong>
                    <span class="badge badge-{{ $contact_message->status === 'new' ? 'warning' : ($contact_message->status === 'replied' ? 'success' : 'secondary') }} mr-2">
                        {{ \App\Models\ContactMessage::STATUSES[$contact_message->status] ?? $contact_message->status }}
                    </span>
                    <small class="text-muted">{{ $contact_message->created_at->format('Y-m-d H:i') }}</small>
                </div>
                <div class="card-body">
                    <p><strong>المرسل:</strong> {{ $contact_message->name }} &lt;{{ $contact_message->email }}&gt;</p>
                    @if($contact_message->user_id)
                        <p><strong>مستخدم مسجل:</strong> {{ $contact_message->user->name ?? '#' . $contact_message->user_id }}</p>
                    @endif
                    <hr>
                    <div class="mb-4">
                        <strong>الرسالة:</strong>
                        <div class="mt-2 p-3 bg-light rounded">{{ nl2br(e($contact_message->message)) }}</div>
                    </div>

                    @if($contact_message->admin_reply)
                        <div class="mb-4">
                            <strong>رد الإدارة ({{ $contact_message->replied_at?->format('Y-m-d H:i') }}):</strong>
                            <div class="mt-2 p-3 bg-light rounded">{{ nl2br(e($contact_message->admin_reply)) }}</div>
                        </div>
                    @endif

                    <hr>
                    <form action="{{ route('dashboard.contact_messages.update', $contact_message) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="admin_reply">الرد (اختياري)</label>
                            <textarea name="admin_reply" id="admin_reply" rows="4" class="form-control @error('admin_reply') is-invalid @enderror" placeholder="اكتب ردك هنا...">{{ old('admin_reply', $contact_message->admin_reply) }}</textarea>
                            @error('admin_reply')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="status">الحالة</label>
                            <select name="status" id="status" class="form-control">
                                @foreach(\App\Models\ContactMessage::STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $contact_message->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">حفظ الرد / تحديث الحالة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
