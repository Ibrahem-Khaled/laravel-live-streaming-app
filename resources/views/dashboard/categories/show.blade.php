@extends('layouts.dashboard.app')

@section('title', $category->name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">{{ $category->name }}</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-primary">
                        <i class="fe fe-edit-2 fe-16"></i> تعديل
                    </a>
                    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-body text-center">
                            @if($category->image)
                                <img src="{{ filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-fluid rounded mb-3" 
                                     style="max-height: 300px;">
                            @else
                                <div class="avatar avatar-xl rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto mb-3">
                                    <i class="fe fe-folder fe-48 text-muted"></i>
                                </div>
                            @endif
                            <h4>{{ $category->name }}</h4>
                            @if($category->is_featured)
                                <span class="badge badge-warning">مميز</span>
                            @endif
                            @if($category->is_active)
                                <span class="badge badge-success">نشط</span>
                            @else
                                <span class="badge badge-secondary">غير نشط</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">التفاصيل</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">الاسم:</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>الرابط:</th>
                                    <td><code>{{ $category->slug }}</code></td>
                                </tr>
                                @if($category->description)
                                <tr>
                                    <th>الوصف:</th>
                                    <td>{{ $category->description }}</td>
                                </tr>
                                @endif
                                @if($category->parent)
                                <tr>
                                    <th>الفئة الرئيسية:</th>
                                    <td>
                                        <a href="{{ route('dashboard.categories.show', $category->parent) }}">{{ $category->parent->name }}</a>
                                    </td>
                                </tr>
                                @endif
                                @if($category->content_type)
                                <tr>
                                    <th>نوع المحتوى:</th>
                                    <td><span class="badge badge-info">{{ \App\Models\Category::CONTENT_TYPES[$category->content_type] }}</span></td>
                                </tr>
                                @endif
                                <tr>
                                    <th>عدد الفئات الفرعية:</th>
                                    <td>{{ $category->children->count() }}</td>
                                </tr>
                                <tr>
                                    <th>عدد المحتويات:</th>
                                    <td>{{ $category->contents->count() }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
