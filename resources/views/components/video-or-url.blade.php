@props([
    'name' => 'stream',
    'label' => 'رابط البث / ملف الفيديو',
    'streamUrl' => null,
    'required' => false,
    'folder' => 'videos',
    'accept' => 'video/*,.m3u8',
])

@php
    $isUrl = $streamUrl && filter_var($streamUrl, FILTER_VALIDATE_URL);
    $isFile = $streamUrl && !$isUrl;
    $inputId = 'stream_' . str_replace(['[', ']'], ['_', ''], $name);
@endphp

<div class="form-group">
    <label>{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    
    <div class="mb-2">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $isUrl ? '' : 'active' }}" data-toggle="tab" href="#{{ $inputId }}_file_tab" role="tab">
                    <i class="fe fe-upload fe-14 mr-1"></i> رفع ملف
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $isUrl ? 'active' : '' }}" data-toggle="tab" href="#{{ $inputId }}_url_tab" role="tab">
                    <i class="fe fe-link fe-14 mr-1"></i> رابط
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <!-- رفع ملف -->
        <div class="tab-pane fade {{ $isUrl ? '' : 'show active' }}" id="{{ $inputId }}_file_tab" role="tabpanel">
            <div class="custom-file">
                <input type="file" 
                       name="{{ $name }}_file" 
                       id="{{ $inputId }}_file" 
                       class="custom-file-input @error($name . '_file') is-invalid @enderror" 
                       accept="{{ $accept }}"
                       onchange="handleVideoFile(this, '{{ $inputId }}_file_info')">
                <label class="custom-file-label" for="{{ $inputId }}_file">اختر ملف فيديو...</label>
                @error($name . '_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div id="{{ $inputId }}_file_info" class="mt-2 small text-muted"></div>
            
            @if($isFile)
                <div class="alert alert-info mt-3">
                    <i class="fe fe-info fe-14 mr-1"></i>
                    ملف موجود: <code>{{ basename($streamUrl) }}</code>
                    <label class="custom-control custom-checkbox mt-2 mb-0">
                        <input type="checkbox" name="remove_{{ $name }}" value="1" class="custom-control-input">
                        <span class="custom-control-label">حذف الملف الحالي</span>
                    </label>
                </div>
            @endif
        </div>

        <!-- رابط -->
        <div class="tab-pane fade {{ $isUrl ? 'show active' : '' }}" id="{{ $inputId }}_url_tab" role="tabpanel">
            <input type="url" 
                   name="{{ $name }}_url" 
                   id="{{ $inputId }}_url" 
                   class="form-control @error($name . '_url') is-invalid @enderror" 
                   value="{{ old($name . '_url', $isUrl ? $streamUrl : '') }}" 
                   placeholder="https://example.com/video.mp4 أو rtmp://...">
            @error($name . '_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            @if($isUrl)
                <div class="mt-3">
                    <div class="alert alert-success">
                        <i class="fe fe-check-circle fe-14 mr-1"></i>
                        رابط صحيح: <a href="{{ $streamUrl }}" target="_blank" class="text-break">{{ Str::limit($streamUrl, 60) }}</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleVideoFile(input, infoId) {
    var info = document.getElementById(infoId);
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        info.innerHTML = '<i class="fe fe-file fe-14 mr-1"></i> ' + file.name + ' (' + sizeMB + ' MB)';
        info.classList.remove('d-none');
    } else {
        info.innerHTML = '';
    }
}

// تحديث label عند اختيار ملف
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function() {
        var label = this.nextElementSibling;
        if (this.files && this.files[0]) {
            label.textContent = this.files[0].name;
        } else {
            label.textContent = 'اختر ملف...';
        }
    });
});
</script>
@endpush
