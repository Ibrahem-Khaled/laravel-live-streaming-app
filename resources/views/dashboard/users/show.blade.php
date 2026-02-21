@extends('layouts.dashboard.app')

@section('title', 'تفاصيل المستخدم')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">تفاصيل المستخدم</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.users.edit', $user) }}" class="btn btn-primary">تعديل</a>
                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">الاسم</dt>
                        <dd class="col-sm-9">{{ $user->name }}</dd>

                        <dt class="col-sm-3">البريد الإلكتروني</dt>
                        <dd class="col-sm-9">{{ $user->email }}</dd>

                        <dt class="col-sm-3">اسم المستخدم</dt>
                        <dd class="col-sm-9">{{ $user->username }}</dd>

                        <dt class="col-sm-3">رقم الجوال</dt>
                        <dd class="col-sm-9">{{ $user->phone ?? '—' }}</dd>

                        <dt class="col-sm-3">الدور</dt>
                        <dd class="col-sm-9"><span class="badge badge-secondary">{{ $user->role_label }}</span></dd>

                        <dt class="col-sm-3">الحالة</dt>
                        <dd class="col-sm-9">
                            @if($user->status === 'active')
                                <span class="badge badge-success">نشط</span>
                            @elseif($user->status === 'inactive')
                                <span class="badge badge-warning">غير نشط</span>
                            @else
                                <span class="badge badge-danger">محظور</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">تاريخ التسجيل</dt>
                        <dd class="col-sm-9">{{ $user->created_at->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-3">آخر تحديث</dt>
                        <dd class="col-sm-9">{{ $user->updated_at->format('Y-m-d H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
