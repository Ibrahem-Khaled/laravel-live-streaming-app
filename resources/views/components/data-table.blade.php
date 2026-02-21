@props([
    'checkboxes' => false,
    'selectAllId' => 'table-select-all',
    'rowCheckboxClass' => 'row-checkbox',
])

<div {{ $attributes->merge(['class' => 'table-responsive']) }}>
    <table class="table table-borderless table-hover mb-0">
        <thead>
            <tr>
                {{ $head }}
            </tr>
        </thead>
        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>

@if($checkboxes && $selectAllId && $rowCheckboxClass)
@once
@push('scripts')
<script>
(function() {
    var id = @json($selectAllId);
    var rowClass = @json($rowCheckboxClass);
    var all = document.getElementById(id);
    if (all) {
        all.addEventListener('change', function() {
            document.querySelectorAll('.' + rowClass).forEach(function(cb) { cb.checked = all.checked; });
        });
    }
})();
</script>
@endpush
@endonce
@endif
