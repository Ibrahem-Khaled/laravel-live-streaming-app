@extends('layouts.dashboard.app')

@section('title', 'تفاصيل الإشعار المرسل')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">تفاصيل الإشعار: {{ Str::limit($broadcast->title, 60) }}</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.notifications.broadcasts.edit', $broadcast) }}" class="btn btn-outline-primary">تعديل</a>
                    <form action="{{ route('dashboard.notifications.broadcasts.destroy', $broadcast) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا الإشعار؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">حذف</button>
                    </form>
                    <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-secondary">رجوع للإشعارات</a>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>محتوى الإشعار</strong>
                    <span class="badge badge-secondary">{{ $broadcast->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="card-body">
                    <p><strong>العنوان:</strong> {{ $broadcast->title }}</p>
                    <p><strong>النص:</strong></p>
                    <div class="p-3 bg-light rounded">{{ nl2br(e($broadcast->body)) }}</div>
                    @if($broadcast->url)
                        <p class="mt-2"><strong>الرابط:</strong> <a href="{{ $broadcast->url }}" target="_blank" rel="noopener">{{ $broadcast->url }}</a></p>
                    @endif
                    <p class="text-muted small mb-0">المرسل: {{ $broadcast->sender->name ?? '—' }} — عدد المستلمين: {{ $broadcast->recipients_count }}</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>المستلمون — من شاف الإشعار</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المستخدم</th>
                                    <th>البريد / اسم المستخدم</th>
                                    <th>حالة القراءة</th>
                                    <th>وقت القراءة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recipients as $r)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $r->user->name ?? '—' }}</td>
                                        <td>
                                            <small>{{ $r->user->email ?? '—' }}</small>
                                            @if($r->user && $r->user->username)
                                                <br><small class="text-muted">{{ $r->user->username ? '@'.$r->user->username : '' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($r->read_at)
                                                <span class="badge badge-success">شاف الإشعار</span>
                                            @else
                                                <span class="badge badge-secondary">لم يقرأ بعد</span>
                                            @endif
                                        </td>
                                        <td>{{ $r->read_at ? $r->read_at->format('Y-m-d H:i') : '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">لا يوجد مستلمون مسجلون.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
