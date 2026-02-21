@extends('layouts.dashboard.app')

@section('title', 'السلايدر')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">السلايدر</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.sliders.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus fe-16"></i> إضافة سلايدر
                    </a>
                </div>
            </div>

            <x-alert />

            <div class="row">
                <x-stat-card title="إجمالي السلايدر" :value="$stats['total']" icon="fe-image" color="primary" :href="route('dashboard.sliders.index')" />
                <x-stat-card title="نشط" :value="$stats['active']" icon="fe-check-circle" color="success" :href="route('dashboard.sliders.index', array_merge(request()->query(), ['is_active' => '1']))" />
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="get" class="form mb-3">
                        <div class="form-row">
                            <div class="form-group col-auto">
                                <select name="is_active" class="custom-select custom-select-sm" onchange="this.form.submit()">
                                    <option value="">كل الحالات</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                            </div>
                            @if(request()->has('is_active'))
                                <a href="{{ route('dashboard.sliders.index') }}" class="btn btn-sm btn-outline-secondary">إعادة تعيين</a>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>العنوان</th>
                                    <th>الرابط</th>
                                    <th>الترتيب</th>
                                    <th>الحالة</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sliders as $slider)
                                    <tr>
                                        <td>{{ $slider->id }}</td>
                                        <td>
                                            @if($slider->image)
                                                <img src="{{ filter_var($slider->image, FILTER_VALIDATE_URL) ? $slider->image : asset('storage/' . $slider->image) }}" alt="" class="img-thumbnail" style="max-width: 80px; max-height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $slider->title ?: '—' }}</strong>
                                            @if($slider->subtitle)
                                                <br><small class="text-muted">{{ Str::limit($slider->subtitle, 40) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $slider->link ? Str::limit($slider->link, 30) : '—' }}</td>
                                        <td>{{ $slider->order }}</td>
                                        <td>
                                            @if($slider->is_active)
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-secondary">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('dashboard.sliders.edit', $slider) }}" class="btn btn-outline-primary" title="تعديل">تعديل</a>
                                                <form action="{{ route('dashboard.sliders.status', $slider) }}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_active" value="{{ $slider->is_active ? 0 : 1 }}">
                                                    <button type="submit" class="btn btn-outline-secondary">{{ $slider->is_active ? 'إيقاف' : 'تفعيل' }}</button>
                                                </form>
                                                <form action="{{ route('dashboard.sliders.destroy', $slider) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا السلايدر؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">حذف</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">لا توجد عناصر.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($sliders->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $sliders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
