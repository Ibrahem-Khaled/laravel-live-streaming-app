@extends('layouts.dashboard.app')

@section('title', 'تعديل الإشعار')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">تعديل الإشعار</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.notifications.broadcasts.show', $broadcast) }}" class="btn btn-outline-primary">عرض</a>
                    <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <p class="text-muted small">التعديل يغيّر العنوان والنص والرابط في سجل الإشعار فقط (للعرض في لوحة التحكم). الإشعارات التي وصلت للمستلمين سابقاً لا تتغيّر.</p>
                    <form action="{{ route('dashboard.notifications.broadcasts.update', $broadcast) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">العنوان <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $broadcast->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="body">النص</label>
                            <textarea name="body" id="body" rows="4" class="form-control @error('body') is-invalid @enderror">{{ old('body', $broadcast->body) }}</textarea>
                            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="url">الرابط</label>
                            <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $broadcast->url) }}" placeholder="https://">
                            @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">حفظ التعديل</button>
                        <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
