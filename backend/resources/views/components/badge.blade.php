@props(['type' => 'primary', 'icon' => null])

@php
    $classes = match($type) {
        'primary' => 'bg-indigo-100 text-indigo-800',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        'slate' => 'bg-slate-100 text-slate-800',
        default => 'bg-indigo-100 text-indigo-800',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center space-x-1 px-3 py-1 text-xs font-semibold rounded-full $classes"]) }}>
    @if($icon)
        <span>{{ $icon }}</span>
    @endif
    <span>{{ $slot }}</span>
</span>
