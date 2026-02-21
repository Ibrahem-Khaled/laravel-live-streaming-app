@extends('layouts.dashboard.app')

@section('title', 'رسائل التواصل')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">رسائل التواصل / الدعم الفني</h2>
                </div>
            </div>

            <x-alert />

            <div class="row">
                <x-stat-card title="إجمالي الرسائل" :value="$stats['total']" icon="fe-mail" color="primary" :href="route('dashboard.contact_messages.index')" />
                <x-stat-card title="جديد" :value="$stats['new']" icon="fe-bell" color="warning" :href="route('dashboard.contact_messages.index', array_merge(request()->query(), ['status' => 'new']))" />
                <x-stat-card title="تم الرد" :value="$stats['replied']" icon="fe-check-circle" color="success" :href="route('dashboard.contact_messages.index', array_merge(request()->query(), ['status' => 'replied']))" />
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="get" class="form mb-3">
                        <div class="form-row">
                            <div class="form-group col-auto">
                                <select name="status" class="custom-select custom-select-sm" onchange="this.form.submit()">
                                    <option value="">كل الحالات</option>
                                    @foreach(\App\Models\ContactMessage::STATUSES as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(request()->has('status'))
                                <a href="{{ route('dashboard.contact_messages.index') }}" class="btn btn-sm btn-outline-secondary">إعادة تعيين</a>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المرسل</th>
                                    <th>الموضوع</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $msg)
                                    <tr>
                                        <td>{{ $msg->id }}</td>
                                        <td>
                                            <strong>{{ $msg->name }}</strong>
                                            <br><small class="text-muted">{{ $msg->email }}</small>
                                            @if($msg->user_id)
                                                <br><span class="badge badge-info">مسجل</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($msg->subject, 50) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $msg->status === 'new' ? 'warning' : ($msg->status === 'replied' ? 'success' : 'secondary') }}">
                                                {{ \App\Models\ContactMessage::STATUSES[$msg->status] ?? $msg->status }}
                                            </span>
                                        </td>
                                        <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('dashboard.contact_messages.show', $msg) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">لا توجد رسائل.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($messages->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
