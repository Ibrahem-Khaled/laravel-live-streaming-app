@php
    $hasChildren = $category->children->isNotEmpty();
@endphp

<div class="category-item depth-{{ $depth }}">
    <div class="category-header">
        <div class="category-info">
            @if($hasChildren)
                <span class="category-toggle">
                    <i class="fe fe-chevron-right fe-16"></i>
                </span>
            @else
                <span class="category-toggle" style="opacity: 0;">
                    <i class="fe fe-chevron-right fe-16"></i>
                </span>
            @endif
            
            @if($category->image)
                <img src="{{ filter_var($category->image, FILTER_VALIDATE_URL) ? $category->image : asset('storage/' . $category->image) }}" 
                     alt="{{ $category->name }}" 
                     class="rounded mr-2" 
                     style="width: 40px; height: 40px; object-fit: cover;">
            @else
                <div class="avatar avatar-md rounded-circle bg-light border d-flex align-items-center justify-content-center mr-2" style="min-width: 40px; min-height: 40px;">
                    <i class="fe fe-folder fe-16 text-muted"></i>
                </div>
            @endif
            
            <div class="flex-grow-1">
                <h6 class="mb-0">
                    {{ $category->name }}
                    @if($category->is_featured)
                        <span class="badge badge-warning ml-1">مميز</span>
                    @endif
                    @if(!$category->is_active)
                        <span class="badge badge-secondary ml-1">غير نشط</span>
                    @endif
                </h6>
                <small class="text-muted">
                    @if($category->content_type)
                        <span class="badge badge-info">{{ \App\Models\Category::CONTENT_TYPES[$category->content_type] }}</span>
                    @endif
                    @if($category->parent)
                        <span class="mr-2">← {{ $category->parent->name }}</span>
                    @endif
                </small>
            </div>
        </div>
        
        <div class="category-actions">
            <button type="button" 
                    class="btn btn-sm btn-outline-secondary category-actions-btn" 
                    title="إجراءات"
                    data-edit-url="{{ route('dashboard.categories.edit', $category) }}"
                    data-status-url="{{ route('dashboard.categories.status', $category) }}"
                    data-delete-url="{{ route('dashboard.categories.destroy', $category) }}"
                    data-is-active="{{ $category->is_active ? '1' : '0' }}">
                <i class="fe fe-more-horizontal fe-16"></i>
            </button>
        </div>
    </div>
    
    @if($hasChildren)
        <div class="category-children {{ $depth < 2 ? 'show' : '' }}">
            @foreach($category->children as $child)
                @include('dashboard.categories.partials.tree-item', ['category' => $child, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>
