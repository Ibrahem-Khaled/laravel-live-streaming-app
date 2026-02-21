@extends('layouts.dashboard.app')

@section('title', 'إرسال إشعار جديد')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">إرسال إشعار للمستخدمين</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-secondary">رجوع للإشعارات</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('dashboard.notifications.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="title">العنوان <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="body">النص <span class="text-danger">*</span></label>
                            <textarea name="body" id="body" rows="4" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="url">رابط (اختياري)</label>
                            <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" placeholder="https://">
                            @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>الجمهور <span class="text-danger">*</span></label>
                            <div>
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="audience" value="all" class="custom-control-input" {{ old('audience', 'all') === 'all' ? 'checked' : '' }}>
                                    <span class="custom-control-label">جميع المستخدمين</span>
                                </label>
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="audience" value="admins" class="custom-control-input" {{ old('audience') === 'admins' ? 'checked' : '' }}>
                                    <span class="custom-control-label">المدراء فقط</span>
                                </label>
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="audience" value="users" class="custom-control-input" {{ old('audience') === 'users' ? 'checked' : '' }}>
                                    <span class="custom-control-label">المستخدمون فقط</span>
                                </label>
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="audience" value="roles" class="custom-control-input" {{ old('audience') === 'roles' ? 'checked' : '' }}>
                                    <span class="custom-control-label">أدوار محددة</span>
                                </label>
                                <label class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="audience" value="selected" class="custom-control-input" {{ old('audience') === 'selected' ? 'checked' : '' }}>
                                    <span class="custom-control-label">مستخدمون محددون</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="roles-group" style="display: none;">
                            <label>الأدوار</label>
                            @foreach(\App\Models\User::ROLES as $value => $label)
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" name="roles[]" value="{{ $value }}" class="custom-control-input" {{ in_array($value, old('roles', [])) ? 'checked' : '' }}>
                                    <span class="custom-control-label">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="form-group" id="user-ids-group" style="display: none;">
                            <label for="user_ids_select">اختر المستلمين</label>
                            <select name="user_ids[]" id="user_ids_select" class="form-control" multiple="multiple" style="width: 100%;">
                                @foreach($preselectedUsers ?? [] as $u)
                                    <option value="{{ $u->id }}" selected="selected">{{ $u->name }} — {{ $u->username ? '@'.$u->username : '' }} — {{ $u->email }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">ابحث واختر المستلمين.</small>
                            @error('user_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">إرسال الإشعار</button>
                        <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('input[name="audience"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('roles-group').style.display = this.value === 'roles' ? 'block' : 'none';
        var userIdsGroup = document.getElementById('user-ids-group');
        userIdsGroup.style.display = this.value === 'selected' ? 'block' : 'none';
        if (this.value === 'selected' && typeof $ !== 'undefined' && $('#user_ids_select').length && !$('#user_ids_select').hasClass('select2-hidden-accessible')) {
            initUserSelect2();
        }
    });
});
var aud = document.querySelector('input[name="audience"]:checked');
if (aud) aud.dispatchEvent(new Event('change'));

function initUserSelect2() {
    var $select = $('#user_ids_select');
    if ($select.length === 0) return;
    $select.select2({
        theme: 'bootstrap4',
        multiple: true,
        placeholder: 'ابحث واختر المستلمين...',
        allowClear: true,
        dir: 'rtl',
        ajax: {
            url: '{{ route("dashboard.api.users.search") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return { q: params.term, page: params.page || 1, per_page: 20 };
            },
            processResults: function(data) {
                return { results: data.results, pagination: data.pagination };
            },
            cache: true
        },
        minimumInputLength: 0
    });
}
if (document.querySelector('input[name="audience"][value="selected"]:checked')) {
    $(document).ready(function() { initUserSelect2(); });
}
</script>
@endpush
@endsection
