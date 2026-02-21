@extends('layouts.dashboard.app')

@section('title', 'الحلقات')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">الحلقات @if($content) - {{ $content->name }} @endif</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.episodes.create', $content ? ['content' => $content->id] : []) }}" class="btn btn-primary">
                        <i class="fe fe-plus fe-16"></i> إضافة حلقة
                    </a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="toolbar mb-3">
                        <form method="get" class="form">
                            <div class="form-row">
                                <div class="form-group col-auto">
                                    <select name="content_id" class="custom-select custom-select-sm" onchange="this.form.submit()">
                                        <option value="">كل المسلسلات</option>
                                        @foreach($contents as $c)
                                            <option value="{{ $c->id }}" {{ request('content_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-auto">
                                    <input type="number" name="season_number" value="{{ request('season_number') }}" class="form-control form-control-sm" placeholder="الموسم" min="1">
                                </div>
                                <div class="form-group col-auto">
                                    <button type="submit" class="btn btn-sm btn-secondary">بحث</button>
                                    @if(request()->hasAny(['content_id','season_number']))
                                        <a href="{{ route('dashboard.episodes.index') }}" class="btn btn-sm btn-outline-secondary">إعادة</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <x-data-table>
                        <x-slot:head>
                            <th>#</th>
                            <th>المسلسل</th>
                            <th>الموسم</th>
                            <th>الحلقة</th>
                            <th>العنوان</th>
                            <th>إجراءات</th>
                        </x-slot:head>
                        <x-slot:body>
                            @forelse($episodes as $episode)
                                <tr>
                                    <td>{{ $episode->id }}</td>
                                    <td>{{ $episode->content->name }}</td>
                                    <td><span class="badge badge-info">S{{ str_pad($episode->season_number, 2, '0', STR_PAD_LEFT) }}</span></td>
                                    <td><span class="badge badge-secondary">E{{ str_pad($episode->episode_number, 2, '0', STR_PAD_LEFT) }}</span></td>
                                    <td>{{ $episode->title ?: $episode->season_episode_label }}</td>
                                    <td>
                                        @if($episode->stream_url)
                                            <a href="{{ route('dashboard.episodes.preview', $episode) }}" class="btn btn-sm btn-outline-success mr-1" title="معاينة" target="_blank">
                                                <i class="fe fe-play-circle fe-14"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('dashboard.episodes.edit', $episode) }}" class="btn btn-sm btn-outline-primary mr-1">
                                            <i class="fe fe-edit-2 fe-14"></i>
                                        </a>
                                        <form action="{{ route('dashboard.episodes.destroy', $episode) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذه الحلقة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fe fe-trash-2 fe-14"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">لا توجد حلقات.</td>
                                </tr>
                            @endforelse
                        </x-slot:body>
                    </x-data-table>

                    @if($episodes->hasPages())
                        <nav aria-label="ترقيم الصفحات" class="mt-3 mb-0">
                            {{ $episodes->links() }}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
