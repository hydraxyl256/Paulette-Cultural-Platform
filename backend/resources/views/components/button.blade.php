@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'button'])

@php
    $baseClasses = 'font-medium rounded-lg transition inline-flex items-center justify-center space-x-2';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };
    
    $variantClasses = match($variant) {
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800',
        'secondary' => 'bg-slate-100 text-slate-900 hover:bg-slate-200 active:bg-slate-300',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800',
        'success' => 'bg-green-600 text-white hover:bg-green-700 active:bg-green-800',
        'outline' => 'border border-slate-300 text-slate-700 hover:bg-slate-50 active:bg-slate-100',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700',
    };
    
    $classes = "$baseClasses $sizeClasses $variantClasses";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
