@extends('layouts.dashboard.app')

@section('title', 'المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">المستخدمين</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus fe-16"></i> إضافة مستخدم
                    </a>
                </div>
            </div>

            <x-alert />

            <div class="row">
                <x-stat-card title="إجمالي المستخدمين" :value="$stats['total']" icon="fe-users" color="primary" :href="route('dashboard.users.index')" />
                <x-stat-card title="نشط" :value="$stats['active']" icon="fe-check-circle" color="success" :href="route('dashboard.users.index', array_merge(request()->query(), ['status' => 'active']))" />
                <x-stat-card title="غير نشط" :value="$stats['inactive']" icon="fe-pause-circle" color="warning" :href="route('dashboard.users.index', array_merge(request()->query(), ['status' => 'inactive']))" />
                <x-stat-card title="محظور" :value="$stats['blocked']" icon="fe-slash" color="danger" :href="route('dashboard.users.index', array_merge(request()->query(), ['status' => 'blocked']))" />
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="toolbar">
                        <form method="get" class="form">
                            <div class="form-row">
                                <div class="form-group col-auto mr-auto">
                                    <label class="my-1 mr-2 sr-only" for="status-filter">الحالة</label>
                                    <select name="status" id="status-filter" class="custom-select custom-select-sm mr-sm-2" style="width: auto;" onchange="this.form.submit()">
                                        <option value="">كل الحالات</option>
                                        @foreach(\App\Models\User::STATUSES as $value => $label)
                                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-auto">
                                    <label class="my-1 mr-2 sr-only" for="role-filter">الدور</label>
                                    <select name="role" id="role-filter" class="custom-select custom-select-sm mr-sm-2" style="width: auto;" onchange="this.form.submit()">
                                        <option value="">كل الأدوار</option>
                                        @foreach(\App\Models\User::ROLES as $value => $label)
                                            <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-auto">
                                    <label for="q" class="sr-only">بحث</label>
                                    <input type="text" name="q" id="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="بحث (اسم، بريد، اسم مستخدم)...">
                                </div>
                                <div class="form-group col-auto">
                                    <button type="submit" class="btn btn-sm btn-secondary">بحث</button>
                                    @if(request()->hasAny(['q','status','role']))
                                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-sm btn-outline-secondary mr-1">إعادة تعيين</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <x-data-table :checkboxes="true" select-all-id="users-select-all" row-checkbox-class="user-row-check">
                        <x-slot:head>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="users-select-all">
                                    <label class="custom-control-label" for="users-select-all"></label>
                                </div>
                            </td>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>البريد / اسم المستخدم</th>
                            <th>الجوال</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>التسجيل</th>
                            <th>إجراءات</th>
                        </x-slot:head>
                        <x-slot:body>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input user-row-check" id="user-{{ $user->id }}" value="{{ $user->id }}">
                                            <label class="custom-control-label" for="user-{{ $user->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md rounded-circle bg-light border d-flex align-items-center justify-content-center mr-2" style="min-width: 40px; min-height: 40px;">
                                                <span class="text-muted font-weight-bold">{{ mb_substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="mb-0"><strong class="text-muted">{{ $user->name }}</strong></p>
                                                <small class="text-muted">#{{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 text-muted">{{ $user->email }}</p>
                                        <small class="text-muted">{{ $user->username }}</small>
                                    </td>
                                    <td>
                                        @if($user->phone)
                                            <p class="mb-0 text-muted"><a href="tel:{{ $user->phone }}" class="text-muted">{{ $user->phone }}</a></p>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-secondary">{{ $user->role_label }}</span></td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="badge badge-success">نشط</span>
                                        @elseif($user->status === 'inactive')
                                            <span class="badge badge-warning">غير نشط</span>
                                        @else
                                            <span class="badge badge-danger">محظور</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-secondary user-actions-btn" title="إجراءات"
                                            data-edit-url="{{ route('dashboard.users.edit', $user) }}"
                                            data-status-url="{{ route('dashboard.users.status', $user) }}"
                                            data-delete-url="{{ route('dashboard.users.destroy', $user) }}"
                                            data-user-name="{{ $user->name }}">
                                            <i class="fe fe-more-horizontal fe-16"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">لا يوجد مستخدمون.</td>
                                </tr>
                            @endforelse
                        </x-slot:body>
                    </x-data-table>
                    @if($users->hasPages())
                        <nav aria-label="ترقيم الصفحات" class="mt-3 mb-0">
                            {{ $users->links() }}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- مودال الإجراءات خارج الجدول --}}
<div class="modal fade modal-slide" id="userActionsModal" tabindex="-1" role="dialog" aria-labelledby="userActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userActionsModalLabel">إجراءات المستخدم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3 small" id="userActionsModalUserName"></p>
                <div class="list-group list-group-flush my-n3">
                    <a id="userActionEditLink" href="#" class="list-group-item bg-transparent">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="fe fe-edit-2 fe-24"></span>
                            </div>
                            <div class="col">
                                <small><strong>تعديل المستخدم</strong></small>
                            </div>
                        </div>
                    </a>
                    <div class="list-group-item bg-transparent">
                        <div class="row align-items-center mb-2">
                            <div class="col-auto">
                                <span class="fe fe-toggle-right fe-24"></span>
                            </div>
                            <div class="col">
                                <small><strong>تغيير الحالة</strong></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <form id="userActionFormStatusActive" method="post" class="d-inline w-100">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="btn btn-sm btn-success btn-block">
                                        <i class="fe fe-check-circle fe-14 mr-1"></i> تفعيل
                                    </button>
                                </form>
                            </div>
                            <div class="col-12 mb-2">
                                <form id="userActionFormStatusInactive" method="post" class="d-inline w-100">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="inactive">
                                    <button type="submit" class="btn btn-sm btn-warning btn-block">
                                        <i class="fe fe-pause-circle fe-14 mr-1"></i> إلغاء تفعيل
                                    </button>
                                </form>
                            </div>
                            <div class="col-12">
                                <form id="userActionFormStatusBlocked" method="post" class="d-inline w-100">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="blocked">
                                    <button type="submit" class="btn btn-sm btn-danger btn-block">
                                        <i class="fe fe-slash fe-14 mr-1"></i> حظر
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item bg-transparent">
                        <form id="userActionFormDelete" method="post" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-block">
                                <i class="fe fe-trash-2 fe-14 mr-1"></i> حذف المستخدم
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.user-actions-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var editUrl = this.getAttribute('data-edit-url');
        var statusUrl = this.getAttribute('data-status-url');
        var deleteUrl = this.getAttribute('data-delete-url');
        var userName = this.getAttribute('data-user-name');
        document.getElementById('userActionEditLink').href = editUrl;
        document.getElementById('userActionsModalUserName').textContent = userName;
        document.getElementById('userActionFormStatusActive').action = statusUrl;
        document.getElementById('userActionFormStatusInactive').action = statusUrl;
        document.getElementById('userActionFormStatusBlocked').action = statusUrl;
        document.getElementById('userActionFormDelete').action = deleteUrl;
        $('#userActionsModal').modal('show');
    });
});
</script>
@endpush
@endsection
