@extends('layouts.dashboard.app')

@section('title', 'معاينة - ' . $title)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-3">
                <div class="col">
                    <h2 class="h5 page-title">
                        <i class="fe fe-play-circle fe-20 text-primary mr-2"></i>
                        معاينة: {{ $title }}
                    </h2>
                </div>
                <div class="col-auto">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fe fe-arrow-right fe-16"></i> رجوع
                    </a>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body p-0">
                    <div class="ratio ratio-16x9 bg-dark">
                        @if($streamUrl)
                            <video
                                id="previewPlayer"
                                class="w-100"
                                controls
                                playsinline
                                poster="{{ $content && $content->display_image ? (filter_var($content->display_image, FILTER_VALIDATE_URL) ? $content->display_image : asset('storage/' . $content->display_image)) : '' }}"
                            >
                                <source src="{{ $streamUrl }}" type="{{ str_contains($streamUrl, '.m3u8') ? 'application/x-mpegURL' : 'video/mp4' }}">
                                متصفحك لا يدعم تشغيل الفيديو.
                            </video>
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                <div class="text-center">
                                    <i class="fe fe-film fe-48 mb-3 opacity-50"></i>
                                    <p class="mb-0">لا يوجد رابط بث للمعاينة. أضف رابطاً أو ارفع ملفاً من صفحة التعديل.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($isSeries && $episodes->isNotEmpty())
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fe fe-layers fe-16 mr-2"></i> حلقات المسلسل</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($episodes as $ep)
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <a href="{{ route('dashboard.episodes.preview', $ep) }}" class="text-decoration-none">
                                        <div class="card border h-100 transition hover-shadow">
                                            <div class="card-body py-3">
                                                <span class="badge badge-primary mb-2">S{{ str_pad($ep->season_number, 2, '0', STR_PAD_LEFT) }} E{{ str_pad($ep->episode_number, 2, '0', STR_PAD_LEFT) }}</span>
                                                <h6 class="mb-0 text-dark">{{ $ep->title ?: 'الحلقة ' . $ep->episode_number }}</h6>
                                                @if($ep->duration)
                                                    <small class="text-muted">{{ $ep->duration_formatted }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-shadow:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.transition { transition: all 0.2s ease; }
#previewPlayer { max-height: 70vh; }
</style>
@endpush

@push('scripts')
@if($streamUrl)
<script src="https://cdn.jsdelivr.net/npm/hls.js@1.4.12"></script>
<script>
(function() {
    var video = document.getElementById('previewPlayer');
    if (!video) return;
    var src = @json($streamUrl);

    if (src && src.indexOf('.m3u8') !== -1 && typeof Hls !== 'undefined' && Hls.isSupported()) {
        var hls = new Hls();
        hls.loadSource(src);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function() {
            video.play().catch(function() {});
        });
    } else if (src && video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = src;
        video.addEventListener('loadedmetadata', function() {
            video.play().catch(function() {});
        });
    }
})();
</script>
@endif
@endpush
@endsection
