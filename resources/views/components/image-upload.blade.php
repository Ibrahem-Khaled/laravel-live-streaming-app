@props([
    'name' => 'image',
    'label' => 'الصورة',
    'value' => null,
    'required' => false,
    'folder' => 'images',
    'accept' => 'image/*',
])

@php
    $imageUrl = $value ? (filter_var($value, FILTER_VALIDATE_URL) ? $value : asset('storage/' . $value)) : null;
    $inputId = 'image_' . str_replace(['[', ']'], ['_', ''], $name);
@endphp

<div class="form-group">
    <label for="{{ $inputId }}_url">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    
    <div class="mb-2">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#{{ $inputId }}_upload_tab" role="tab">
                    <i class="fe fe-upload fe-14 mr-1"></i> رفع ملف
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#{{ $inputId }}_url_tab" role="tab">
                    <i class="fe fe-link fe-14 mr-1"></i> رابط
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <!-- رفع ملف -->
        <div class="tab-pane fade show active" id="{{ $inputId }}_upload_tab" role="tabpanel">
            <div class="custom-file">
                <input type="file" 
                       name="{{ $name }}" 
                       id="{{ $inputId }}" 
                       class="custom-file-input @error($name) is-invalid @enderror" 
                       accept="{{ $accept }}"
                       onchange="previewImage(this, '{{ $inputId }}_preview')">
                <label class="custom-file-label" for="{{ $inputId }}">اختر ملف...</label>
                @error($name)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            @if($imageUrl)
                <div class="mt-3">
                    <img src="{{ $imageUrl }}" alt="Preview" id="{{ $inputId }}_preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    <div class="mt-2">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" name="remove_{{ $name }}" value="1" class="custom-control-input">
                            <span class="custom-control-label">حذف الصورة</span>
                        </label>
                    </div>
                </div>
            @else
                <div class="mt-3">
                    <img src="" alt="Preview" id="{{ $inputId }}_preview" class="img-thumbnail d-none" style="max-width: 200px; max-height: 200px;">
                </div>
            @endif
        </div>

        <!-- رابط -->
        <div class="tab-pane fade" id="{{ $inputId }}_url_tab" role="tabpanel">
            <input type="url" 
                   name="{{ $name }}_url" 
                   id="{{ $inputId }}_url" 
                   class="form-control @error($name . '_url') is-invalid @enderror" 
                   value="{{ old($name . '_url', filter_var($value ?? '', FILTER_VALIDATE_URL) ? $value : '') }}" 
                   placeholder="https://example.com/image.jpg">
            @error($name . '_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            @if($imageUrl && filter_var($value, FILTER_VALIDATE_URL))
                <div class="mt-3">
                    <img src="{{ $imageUrl }}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// تحديث label عند اختيار ملف
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function() {
        var label = this.nextElementSibling;
        label.textContent = this.files[0] ? this.files[0].name : 'اختر ملف...';
    });
});
</script>
@endpush
