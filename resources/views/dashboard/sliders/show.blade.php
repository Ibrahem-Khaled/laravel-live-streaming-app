@extends('layouts.dashboard.app')

@section('title', 'عرض السلايدر')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">عرض السلايدر</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.sliders.edit', $slider) }}" class="btn btn-primary">تعديل</a>
                    <a href="{{ route('dashboard.sliders.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($slider->image)
                                <img src="{{ filter_var($slider->image, FILTER_VALIDATE_URL) ? $slider->image : asset('storage/' . $slider->image) }}" alt="" class="img-fluid rounded">
                            @else
                                <p class="text-muted">لا توجد صورة</p>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <p><strong>العنوان:</strong> {{ $slider->title ?: '—' }}</p>
                            <p><strong>العنوان الفرعي:</strong> {{ $slider->subtitle ?: '—' }}</p>
                            <p><strong>الرابط:</strong> {{ $slider->link ?: '—' }}</p>
                            <p><strong>الترتيب:</strong> {{ $slider->order }}</p>
                            <p><strong>الحالة:</strong> {{ $slider->is_active ? 'نشط' : 'غير نشط' }}</p>
                            @if($slider->starts_at)
                                <p><strong>بداية العرض:</strong> {{ $slider->starts_at->format('Y-m-d H:i') }}</p>
                            @endif
                            @if($slider->ends_at)
                                <p><strong>نهاية العرض:</strong> {{ $slider->ends_at->format('Y-m-d H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
