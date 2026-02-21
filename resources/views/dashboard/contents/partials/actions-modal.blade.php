<div class="modal fade modal-slide" id="contentActionsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إجراءات المحتوى</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush my-n3">
                    <a id="contentActionEditLink" href="#" class="list-group-item bg-transparent">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="fe fe-edit-2 fe-24"></span>
                            </div>
                            <div class="col">
                                <small><strong>تعديل المحتوى</strong></small>
                            </div>
                        </div>
                    </a>
                    <a id="contentActionPreviewLink" href="#" class="list-group-item bg-transparent d-none" target="_blank">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="fe fe-play-circle fe-24 text-primary"></span>
                            </div>
                            <div class="col">
                                <small><strong>معاينة</strong></small>
                            </div>
                        </div>
                    </a>
                    <div class="list-group-item bg-transparent">
                        <form id="contentActionFormStatus" method="post" class="d-inline w-100">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_active" id="contentActionStatusValue" value="1">
                            <button type="submit" class="btn btn-sm btn-block" id="contentActionStatusBtn">
                                <i class="fe fe-toggle-right fe-14 mr-1"></i> <span id="contentActionStatusText">تفعيل</span>
                            </button>
                        </form>
                    </div>
                    <div class="list-group-item bg-transparent">
                        <form id="contentActionFormFeature" method="post" class="d-inline w-100">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_featured" id="contentActionFeatureValue" value="1">
                            <button type="submit" class="btn btn-sm btn-block" id="contentActionFeatureBtn">
                                <i class="fe fe-star fe-14 mr-1"></i> <span id="contentActionFeatureText">تمييز</span>
                            </button>
                        </form>
                    </div>
                    <div class="list-group-item bg-transparent">
                        <form id="contentActionFormDelete" method="post" onsubmit="return confirm('هل أنت متأكد من حذف هذا المحتوى؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-block">
                                <i class="fe fe-trash-2 fe-14 mr-1"></i> حذف المحتوى
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.content-actions-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var editUrl = this.getAttribute('data-edit-url');
            var previewUrl = this.getAttribute('data-preview-url');
            var statusUrl = this.getAttribute('data-status-url');
            var featureUrl = this.getAttribute('data-feature-url');
            var deleteUrl = this.getAttribute('data-delete-url');
            var isActive = this.getAttribute('data-is-active') === '1';
            var isFeatured = this.getAttribute('data-is-featured') === '1';
            var hasPreview = this.getAttribute('data-has-preview') === '1';
            
            document.getElementById('contentActionEditLink').href = editUrl;
            var previewLink = document.getElementById('contentActionPreviewLink');
            if (previewLink) {
                previewLink.href = previewUrl || '#';
                previewLink.classList.toggle('d-none', !hasPreview);
            }
            document.getElementById('contentActionFormStatus').action = statusUrl;
            document.getElementById('contentActionFormFeature').action = featureUrl;
            document.getElementById('contentActionFormDelete').action = deleteUrl;
            
            document.getElementById('contentActionStatusValue').value = isActive ? '0' : '1';
            document.getElementById('contentActionStatusBtn').className = 'btn btn-sm btn-block ' + (isActive ? 'btn-warning' : 'btn-success');
            document.getElementById('contentActionStatusText').textContent = isActive ? 'إلغاء تفعيل' : 'تفعيل';
            
            document.getElementById('contentActionFeatureValue').value = isFeatured ? '0' : '1';
            document.getElementById('contentActionFeatureBtn').className = 'btn btn-sm btn-block ' + (isFeatured ? 'btn-secondary' : 'btn-warning');
            document.getElementById('contentActionFeatureText').textContent = isFeatured ? 'إلغاء التمييز' : 'تمييز';
            
            $('#contentActionsModal').modal('show');
        });
    });
});
</script>
@endpush
