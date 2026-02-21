@extends('layouts.dashboard.app')

@section('title', 'إضافة سلايدر')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">إضافة سلايدر جديد</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.sliders.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('dashboard.sliders.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">العنوان</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order">الترتيب</label>
                                    <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}" min="0">
                                    @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subtitle">العنوان الفرعي</label>
                            <input type="text" name="subtitle" id="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle') }}">
                            @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="link">الرابط</label>
                            <input type="url" name="link" id="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link') }}" placeholder="https://">
                            @error('link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <x-image-upload name="image" label="الصورة" />

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block">نشط</label>
                                    <label class="custom-control custom-checkbox">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" class="custom-control-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <span class="custom-control-label">عرض السلايدر</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="starts_at">بداية العرض</label>
                                    <input type="datetime-local" name="starts_at" id="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}">
                                    @error('starts_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ends_at">نهاية العرض</label>
                                    <input type="datetime-local" name="ends_at" id="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}">
                                    @error('ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                            <a href="{{ route('dashboard.sliders.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
