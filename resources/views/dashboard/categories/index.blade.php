@extends('layouts.dashboard.app')

@section('title', 'الفئات')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">الفئات</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus fe-16"></i> إضافة فئة
                    </a>
                </div>
            </div>

            <x-alert />

            <div class="row">
                <x-stat-card title="إجمالي الفئات" :value="$stats['total']" icon="fe-folder" color="primary" :href="route('dashboard.categories.index')" />
                <x-stat-card title="نشط" :value="$stats['active']" icon="fe-check-circle" color="success" :href="route('dashboard.categories.index', array_merge(request()->query(), ['is_active' => '1']))" />
                <x-stat-card title="مميز" :value="$stats['featured']" icon="fe-star" color="warning" :href="route('dashboard.categories.index', array_merge(request()->query(), ['is_featured' => '1']))" />
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="toolbar mb-3">
                        <form method="get" class="form">
                            <div class="form-row">
                                <div class="form-group col-auto mr-auto">
                                    <label class="my-1 mr-2 sr-only" for="content_type-filter">نوع المحتوى</label>
                                    <select name="content_type" id="content_type-filter" class="custom-select custom-select-sm mr-sm-2" style="width: auto;" onchange="this.form.submit()">
                                        <option value="">كل الأنواع</option>
                                        @foreach(\App\Models\Category::CONTENT_TYPES as $value => $label)
                                            <option value="{{ $value }}" {{ request('content_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-auto">
                                    <label for="q" class="sr-only">بحث</label>
                                    <input type="text" name="q" id="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="بحث (اسم، وصف)...">
                                </div>
                                <div class="form-group col-auto">
                                    <button type="submit" class="btn btn-sm btn-secondary">بحث</button>
                                    @if(request()->hasAny(['q','content_type']))
                                        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-sm btn-outline-secondary mr-1">إعادة تعيين</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="categories-tree" class="categories-tree">
                        @forelse($rootCategories as $category)
                            @include('dashboard.categories.partials.tree-item', ['category' => $category, 'depth' => 0])
                        @empty
                            <div class="text-center text-muted py-5 empty-state-categories">
                                <i class="fe fe-folder fe-48 mb-3 d-block"></i>
                                <p class="mb-3">لا توجد فئات.</p>
                                <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary">إضافة فئة جديدة</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- مودال الإجراءات --}}
<div class="modal fade modal-slide" id="categoryActionsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إجراءات الفئة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush my-n3">
                    <a id="categoryActionEditLink" href="#" class="list-group-item bg-transparent">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="fe fe-edit-2 fe-24"></span>
                            </div>
                            <div class="col">
                                <small><strong>تعديل الفئة</strong></small>
                            </div>
                        </div>
                    </a>
                    <div class="list-group-item bg-transparent">
                        <form id="categoryActionFormStatus" method="post" class="d-inline w-100">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_active" id="categoryActionStatusValue" value="1">
                            <button type="submit" class="btn btn-sm btn-block" id="categoryActionStatusBtn">
                                <i class="fe fe-toggle-right fe-14 mr-1"></i> <span id="categoryActionStatusText">تفعيل</span>
                            </button>
                        </form>
                    </div>
                    <div class="list-group-item bg-transparent">
                        <form id="categoryActionFormDelete" method="post" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-block">
                                <i class="fe fe-trash-2 fe-14 mr-1"></i> حذف الفئة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.categories-tree {
    list-style: none;
    padding: 0;
}

.category-item {
    padding: 12px;
    margin-bottom: 8px;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 8px;
    background: #fff;
    transition: all 0.2s;
}

/* الوضع الليلي (Dark mode) - يطابق ثيم app-dark.css */
body.dark .category-item,
.vertical.dark .category-item {
    background: #343a40;
    border-color: rgba(255,255,255,0.06);
}

body.dark .category-item:hover,
.vertical.dark .category-item:hover {
    background: #3e444a;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3);
}

body.dark .category-item .text-muted,
.vertical.dark .category-item .text-muted {
    color: #8b8f9a !important;
}

body.dark .category-item h6,
.vertical.dark .category-item h6 {
    color: #e9ecef;
}


body.dark .category-item .avatar.bg-light,
.vertical.dark .category-item .avatar.bg-light {
    background: rgba(255,255,255,0.1) !important;
    border-color: rgba(255,255,255,0.15) !important;
}

.category-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.category-item.depth-0 { margin-right: 0; }
.category-item.depth-1 { margin-right: 30px; }
.category-item.depth-2 { margin-right: 60px; }
.category-item.depth-3 { margin-right: 90px; }

.category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.category-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.category-toggle {
    cursor: pointer;
    margin-left: 10px;
    color: #6c757d;
}

body.dark .category-toggle,
.vertical.dark .category-toggle {
    color: #8b8f9a;
}

.category-children {
    margin-top: 10px;
    margin-right: 20px;
    display: none;
}

.category-children.show {
    display: block;
}

body.dark .empty-state-categories,
.vertical.dark .empty-state-categories {
    color: #8b8f9a !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle collapse/expand
    document.querySelectorAll('.category-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            var children = this.closest('.category-item').querySelector('.category-children');
            if (children) {
                children.classList.toggle('show');
                var icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fe-chevron-down');
                    icon.classList.toggle('fe-chevron-right');
                }
            }
        });
    });

    // Actions modal
    document.querySelectorAll('.category-actions-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var editUrl = this.getAttribute('data-edit-url');
            var statusUrl = this.getAttribute('data-status-url');
            var deleteUrl = this.getAttribute('data-delete-url');
            var isActive = this.getAttribute('data-is-active') === '1';
            
            document.getElementById('categoryActionEditLink').href = editUrl;
            document.getElementById('categoryActionFormStatus').action = statusUrl;
            document.getElementById('categoryActionFormDelete').action = deleteUrl;
            document.getElementById('categoryActionStatusValue').value = isActive ? '0' : '1';
            document.getElementById('categoryActionStatusBtn').className = 'btn btn-sm btn-block ' + (isActive ? 'btn-warning' : 'btn-success');
            document.getElementById('categoryActionStatusText').textContent = isActive ? 'إلغاء تفعيل' : 'تفعيل';
            
            $('#categoryActionsModal').modal('show');
        });
    });
});
</script>
@endpush
@endsection
