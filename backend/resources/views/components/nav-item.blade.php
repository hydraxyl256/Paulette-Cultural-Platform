@props(['icon' => '📌', 'label', 'href' => '#', 'badge' => null])

@php
    $isActive = request()->url() === $href || str_contains(request()->url(), $href);
@endphp

<a href="{{ $href }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition @if($isActive) bg-indigo-500 text-white @else text-slate-300 hover:bg-slate-800 @endif">
    <span class="text-lg">{{ $icon }}</span>
    <span class="text-sm font-medium flex-1">{{ $label }}</span>
    @if($badge)
        <span class="inline-block px-2 py-1 text-xs font-bold rounded-full bg-red-500 text-white">{{ $badge }}</span>
    @endif
</a>
