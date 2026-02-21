@extends('layouts.dashboard.app')

@section('title', 'إضافة حلقة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">إضافة حلقة جديدة</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.episodes.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ $content ? route('dashboard.contents.episodes.store', $content) : route('dashboard.episodes.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="content_id" value="{{ $content ? $content->id : old('content_id') }}">

                        <div class="form-group">
                            <label for="content_id">المسلسل <span class="text-danger">*</span></label>
                            <select name="content_id" id="content_id" class="form-control @error('content_id') is-invalid @enderror" required {{ $content ? 'disabled' : '' }}>
                                <option value="">اختر مسلسل</option>
                                @foreach($contents as $c)
                                    <option value="{{ $c->id }}" {{ ($content && $content->id == $c->id) || old('content_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @if($content)
                                <input type="hidden" name="content_id" value="{{ $content->id }}">
                            @endif
                            @error('content_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="season_number">رقم الموسم <span class="text-danger">*</span></label>
                                    <input type="number" name="season_number" id="season_number" class="form-control @error('season_number') is-invalid @enderror" value="{{ old('season_number', 1) }}" min="1" required>
                                    @error('season_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="episode_number">رقم الحلقة <span class="text-danger">*</span></label>
                                    <input type="number" name="episode_number" id="episode_number" class="form-control @error('episode_number') is-invalid @enderror" value="{{ old('episode_number') }}" min="1" required>
                                    @error('episode_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">عنوان الحلقة</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <x-image-upload name="image" label="صورة الحلقة" />

                        <x-video-or-url name="stream" label="رابط البث / ملف الحلقة" :required="true" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration">المدة (ثانية)</label>
                                    <input type="number" name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}" min="1">
                                    @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aired_at">تاريخ البث</label>
                                    <input type="date" name="aired_at" id="aired_at" class="form-control @error('aired_at') is-invalid @enderror" value="{{ old('aired_at') }}">
                                    @error('aired_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">حفظ الحلقة</button>
                            <a href="{{ route('dashboard.episodes.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
