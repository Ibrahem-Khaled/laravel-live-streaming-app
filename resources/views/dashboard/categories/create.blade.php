@extends('layouts.dashboard.app')

@section('title', 'إضافة فئة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">إضافة فئة جديدة</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('dashboard.categories.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="slug">الرابط (Slug)</label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
                                    <small class="form-text text-muted">سيتم توليده تلقائياً من الاسم إن لم يتم إدخاله</small>
                                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <x-image-upload name="image" label="الصورة" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_id">الفئة الرئيسية</label>
                                    <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                        <option value="">فئة رئيسية (لا يوجد)</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="content_type">نوع المحتوى الافتراضي</label>
                                    <select name="content_type" id="content_type" class="form-control @error('content_type') is-invalid @enderror">
                                        <option value="">جميع الأنواع</option>
                                        @foreach(\App\Models\Category::CONTENT_TYPES as $value => $label)
                                            <option value="{{ $value }}" {{ old('content_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('content_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="icon">الأيقونة</label>
                                    <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="fe fe-folder">
                                    <small class="form-text text-muted">اسم أيقونة Feather Icons</small>
                                    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sort_order">ترتيب العرض</label>
                                    <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>الحالة</label>
                                    <div class="mt-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" name="is_active" value="1" class="custom-control-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <span class="custom-control-label">نشط</span>
                                        </label>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" name="is_featured" value="1" class="custom-control-input" {{ old('is_featured') ? 'checked' : '' }}>
                                            <span class="custom-control-label">مميز</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">حفظ الفئة</button>
                            <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
