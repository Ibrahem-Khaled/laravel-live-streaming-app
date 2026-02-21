@extends('layouts.dashboard.app')

@section('title', 'المحتويات')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">المحتويات</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.contents.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus fe-16"></i> إضافة محتوى
                    </a>
                </div>
            </div>

            <x-alert />

            <div class="row">
                <x-stat-card title="إجمالي المحتويات" :value="$stats['total']" icon="fe-film" color="primary" />
                <x-stat-card title="قنوات" :value="$stats['channels']" icon="fe-tv" color="info" :href="route('dashboard.contents.index', array_merge(request()->query(), ['type' => 'channel']))" />
                <x-stat-card title="أفلام" :value="$stats['movies']" icon="fe-video" color="success" :href="route('dashboard.contents.index', array_merge(request()->query(), ['type' => 'movie']))" />
                <x-stat-card title="مسلسلات" :value="$stats['series']" icon="fe-layers" color="warning" :href="route('dashboard.contents.index', array_merge(request()->query(), ['type' => 'series']))" />
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="toolbar mb-3">
                        <form method="get" class="form">
                            <div class="form-row">
                                <div class="form-group col-auto">
                                    <select name="type" class="custom-select custom-select-sm" onchange="this.form.submit()">
                                        <option value="">كل الأنواع</option>
                                        @foreach(\App\Models\Content::TYPES as $value => $label)
                                            <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-auto">
                                    <select name="category_id" class="custom-select custom-select-sm" onchange="this.form.submit()">
                                        <option value="">كل الفئات</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-auto">
                                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="بحث...">
                                </div>
                                <div class="form-group col-auto">
                                    <button type="submit" class="btn btn-sm btn-secondary">بحث</button>
                                    @if(request()->hasAny(['q','type','category_id']))
                                        <a href="{{ route('dashboard.contents.index') }}" class="btn btn-sm btn-outline-secondary">إعادة</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <x-data-table :checkboxes="true" select-all-id="contents-select-all" row-checkbox-class="content-row-check">
                        <x-slot:head>
                            <td><input type="checkbox" class="custom-control-input" id="contents-select-all"></td>
                            <th>#</th>
                            <th>المحتوى</th>
                            <th>النوع</th>
                            <th>الفئة</th>
                            <th>الحالة</th>
                            <th>المشاهدات</th>
                            <th>إجراءات</th>
                        </x-slot:head>
                        <x-slot:body>
                            @forelse($contents as $content)
                                <tr>
                                    <td><input type="checkbox" class="custom-control-input content-row-check" value="{{ $content->id }}"></td>
                                    <td>{{ $content->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($content->display_image)
                                                <img src="{{ filter_var($content->display_image, FILTER_VALIDATE_URL) ? $content->display_image : asset('storage/' . $content->display_image) }}" 
                                                     alt="{{ $content->name }}" 
                                                     class="rounded mr-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $content->name }}</strong>
                                                @if($content->is_featured)
                                                    <span class="badge badge-warning ml-1">مميز</span>
                                                @endif
                                                @if($content->year)
                                                    <small class="text-muted d-block">{{ $content->year }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-secondary">{{ $content->type_label }}</span></td>
                                    <td>{{ $content->category ? $content->category->name : '—' }}</td>
                                    <td>
                                        @if($content->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($content->views_count) }}</td>
                                    <td>
                                        @if($content->stream_url || ($content->type === 'series' && $content->episodes_count > 0))
                                            <a href="{{ route('dashboard.contents.preview', $content) }}" class="btn btn-sm btn-outline-primary mr-1" title="معاينة" target="_blank">
                                                <i class="fe fe-play-circle fe-16"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-secondary content-actions-btn" 
                                                data-edit-url="{{ route('dashboard.contents.edit', $content) }}"
                                                data-preview-url="{{ route('dashboard.contents.preview', $content) }}"
                                                data-status-url="{{ route('dashboard.contents.status', $content) }}"
                                                data-feature-url="{{ route('dashboard.contents.feature', $content) }}"
                                                data-delete-url="{{ route('dashboard.contents.destroy', $content) }}"
                                                data-is-active="{{ $content->is_active ? '1' : '0' }}"
                                                data-is-featured="{{ $content->is_featured ? '1' : '0' }}"
                                                data-has-preview="{{ ($content->stream_url || ($content->type === 'series' && $content->episodes_count > 0)) ? '1' : '0' }}">
                                            <i class="fe fe-more-horizontal fe-16"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">لا يوجد محتوى.</td>
                                </tr>
                            @endforelse
                        </x-slot:body>
                    </x-data-table>

                    @if($contents->hasPages())
                        <nav aria-label="ترقيم الصفحات" class="mt-3 mb-0">
                            {{ $contents->links() }}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- مودال الإجراءات --}}
@include('dashboard.contents.partials.actions-modal')
@endsection
