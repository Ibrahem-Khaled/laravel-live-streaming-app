@extends('layouts.dashboard.app')

@section('title', 'الإشعارات المرسلة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">الإشعارات المرسلة</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.notifications.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus fe-16"></i> إرسال إشعار جديد
                    </a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>قائمة الإشعارات</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>التاريخ</th>
                                    <th>عدد المستلمين</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($broadcasts as $b)
                                    <tr>
                                        <td>{{ $b->id }}</td>
                                        <td>{{ Str::limit($b->title, 50) }}</td>
                                        <td>{{ $b->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $b->recipients_count }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('dashboard.notifications.broadcasts.show', $b) }}" class="btn btn-outline-primary" title="عرض ومن شافها">عرض</a>
                                                <a href="{{ route('dashboard.notifications.broadcasts.edit', $b) }}" class="btn btn-outline-secondary" title="تعديل">تعديل</a>
                                                <form action="{{ route('dashboard.notifications.broadcasts.destroy', $b) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا الإشعار؟ سيُحذف من صناديق المستلمين أيضاً.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="حذف">حذف</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            لا توجد إشعارات مرسلة بعد.
                                            <a href="{{ route('dashboard.notifications.create') }}" class="d-block mt-2">إرسال أول إشعار</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($broadcasts->hasPages())
                        <div class="d-flex justify-content-center mt-3">{{ $broadcasts->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
