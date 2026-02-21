@props([
    'title' => '',
    'value' => 0,
    'icon' => 'fe-bar-chart-2',
    'color' => 'primary',
    'href' => null,
])

@php
    $colors = [
        'primary' => 'primary',
        'success' => 'success',
        'warning' => 'warning',
        'danger' => 'danger',
        'info' => 'info',
    ];
    $color = $colors[$color] ?? 'primary';
@endphp

<div {{ $attributes->merge(['class' => 'col-md-6 col-xl-3 mb-4']) }}>
    <div class="card shadow border-0">
        @if($href)
            <a href="{{ $href }}" class="text-decoration-none text-dark">
        @endif
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="rounded p-3 bg-light mr-3">
                    <i class="fe {{ $icon }} fe-24 text-{{ $color }}"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="text-muted mb-1 small">{{ $title }}</p>
                    <h4 class="mb-0 font-weight-bold">{{ number_format($value) }}</h4>
                </div>
            </div>
        </div>
        @if($href)
            </a>
        @endif
    </div>
</div>
