@extends('layouts.dashboard.app')

@section('title', $content->name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">{{ $content->name }}</h2>
                </div>
                <div class="col-auto">
                    @if($content->stream_url || ($content->type === 'series' && $content->episodes_count > 0))
                        <a href="{{ route('dashboard.contents.preview', $content) }}" class="btn btn-success mr-2" target="_blank">
                            <i class="fe fe-play-circle fe-16"></i> معاينة
                        </a>
                    @endif
                    <a href="{{ route('dashboard.contents.edit', $content) }}" class="btn btn-primary">
                        <i class="fe fe-edit-2 fe-16"></i> تعديل
                    </a>
                    <a href="{{ route('dashboard.contents.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-body text-center">
                            @if($content->display_image)
                                <img src="{{ filter_var($content->display_image, FILTER_VALIDATE_URL) ? $content->display_image : asset('storage/' . $content->display_image) }}" 
                                     alt="{{ $content->name }}" 
                                     class="img-fluid rounded mb-3" 
                                     style="max-height: 400px;">
                            @endif
                            <h4>{{ $content->name }}</h4>
                            <span class="badge badge-secondary">{{ $content->type_label }}</span>
                            @if($content->is_featured)
                                <span class="badge badge-warning">مميز</span>
                            @endif
                            @if($content->is_active)
                                <span class="badge badge-success">نشط</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">التفاصيل</h5>
                            <table class="table table-borderless">
                                <tr><th width="150">الاسم:</th><td>{{ $content->name }}</td></tr>
                                <tr><th>النوع:</th><td><span class="badge badge-secondary">{{ $content->type_label }}</span></td></tr>
                                @if($content->category)
                                <tr><th>الفئة:</th><td>{{ $content->category->name }}</td></tr>
                                @endif
                                @if($content->description)
                                <tr><th>الوصف:</th><td>{{ $content->description }}</td></tr>
                                @endif
                                @if($content->year)
                                <tr><th>سنة الإنتاج:</th><td>{{ $content->year }}</td></tr>
                                @endif
                                @if($content->duration)
                                <tr><th>المدة:</th><td>{{ $content->duration }} دقيقة</td></tr>
                                @endif
                                @if($content->rating)
                                <tr><th>التقييم:</th><td>{{ $content->rating }}/10</td></tr>
                                @endif
                                @if($content->quality)
                                <tr><th>الجودة:</th><td><span class="badge badge-info">{{ $content->quality_label }}</span></td></tr>
                                @endif
                                <tr><th>المشاهدات:</th><td>{{ number_format($content->views_count) }}</td></tr>
                                @if($content->type === 'series')
                                <tr><th>عدد الحلقات:</th><td>{{ $content->episodes_count }}</td></tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
