@props([])

@php
    $message = null;
    $type = 'success';

    if (session('success')) {
        $message = session('success');
        $type = 'success';
    } elseif (session('error')) {
        $message = session('error');
        $type = 'danger';
    } elseif (session('warning')) {
        $message = session('warning');
        $type = 'warning';
    } elseif (session('info')) {
        $message = session('info');
        $type = 'info';
    } elseif ($errors->any()) {
        $message = $errors->first();
        $type = 'danger';
    }
@endphp

@if($message)
    <div {{ $attributes->merge(['class' => "alert alert-{$type} alert-dismissible fade show"]) }} role="alert">
        @if($type === 'danger' && $errors->any())
            @if($errors->count() > 1)
                <ul class="mb-0 pr-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            @else
                {{ $message }}
            @endif
        @else
            {{ $message }}
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
