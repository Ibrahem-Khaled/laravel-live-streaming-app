@extends('layouts.dashboard.app')

@section('title', 'تعديل حلقة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">تعديل حلقة: {{ $episode->season_episode_label }}</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.episodes.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <x-alert />

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('dashboard.episodes.update', $episode) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="season_number">رقم الموسم <span class="text-danger">*</span></label>
                                    <input type="number" name="season_number" id="season_number" class="form-control" value="{{ old('season_number', $episode->season_number) }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="episode_number">رقم الحلقة <span class="text-danger">*</span></label>
                                    <input type="number" name="episode_number" id="episode_number" class="form-control" value="{{ old('episode_number', $episode->episode_number) }}" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">عنوان الحلقة</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $episode->title) }}">
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $episode->description) }}</textarea>
                        </div>

                        <x-image-upload name="image" label="صورة الحلقة" :value="$episode->image" />

                        <x-video-or-url name="stream" label="رابط البث / ملف الحلقة" :streamUrl="$episode->stream_url" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="duration">المدة (ثانية)</label>
                                    <input type="number" name="duration" id="duration" class="form-control" value="{{ old('duration', $episode->duration) }}" min="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aired_at">تاريخ البث</label>
                                    <input type="date" name="aired_at" id="aired_at" class="form-control" value="{{ old('aired_at', $episode->aired_at ? $episode->aired_at->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">تحديث الحلقة</button>
                            <a href="{{ route('dashboard.episodes.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
