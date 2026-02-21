@extends('layouts.dashboard.app')

@section('title', 'إضافة محتوى')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">إضافة محتوى جديد</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.contents.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('dashboard.contents.store') }}" method="post" enctype="multipart/form-data" id="contentForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="type">النوع <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required onchange="toggleContentFields()">
                                @foreach(\App\Models\Content::TYPES as $value => $label)
                                    <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

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
                                    <label for="category_id">الفئة</label>
                                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">اختر فئة</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <x-image-upload name="image" label="الصورة" />
                        <x-image-upload name="poster" label="البوستر" />
                        <x-image-upload name="banner" label="البانر" />

                        <div id="streamSection">
                            <x-video-or-url name="stream" label="رابط البث / ملف الفيديو" :required="true" />
                        </div>

                        <div id="movieFields" class="content-type-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year">سنة الإنتاج</label>
                                        <input type="number" name="year" id="year" class="form-control" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 10 }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="duration">المدة (دقيقة)</label>
                                        <input type="number" name="duration" id="duration" class="form-control" value="{{ old('duration') }}" min="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="rating">التقييم (0-10)</label>
                                        <input type="number" name="rating" id="rating" class="form-control" value="{{ old('rating') }}" step="0.1" min="0" max="10">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="quality">الجودة</label>
                                        <select name="quality" id="quality" class="form-control">
                                            <option value="">اختر</option>
                                            @foreach(\App\Models\Content::QUALITIES as $value => $label)
                                                <option value="{{ $value }}" {{ old('quality') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="language">اللغة</label>
                                        <input type="text" name="language" id="language" class="form-control" value="{{ old('language') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">الدولة</label>
                                        <input type="text" name="country" id="country" class="form-control" value="{{ old('country') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">ترتيب العرض</label>
                                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <button type="submit" class="btn btn-primary">حفظ المحتوى</button>
                            <a href="{{ route('dashboard.contents.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleContentFields() {
    var type = document.getElementById('type').value;
    var movieFields = document.getElementById('movieFields');
    var streamSection = document.getElementById('streamSection');
    
    if (type === 'movie' || type === 'series') {
        movieFields.style.display = 'block';
    } else {
        movieFields.style.display = 'none';
    }
    
    if (type === 'series') {
        streamSection.style.display = 'none';
    } else {
        streamSection.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleContentFields();
});
</script>
@endpush
@endsection
