@props(['title' => null, 'subtitle' => null])

<div class="bg-white border border-slate-200 rounded-lg shadow-sm hover:shadow-md transition @if(isset($attributes['class'])) {{ $attributes['class'] }} @endif">
    @if($title)
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-sm text-slate-600 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    <div class="@if($title) px-6 py-6 @else px-6 py-6 @endif">
        {{ $slot }}
    </div>
</div>
