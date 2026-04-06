@php
    $data = $this->getData();
    $colorMap = [
        'emerald' => ['bg' => 'rgba(15, 147, 97, 0.1)', 'border' => '#0f9361', 'icon' => '#27d384'],
        'amber' => ['bg' => 'rgba(214, 120, 0, 0.1)', 'border' => '#d67800', 'icon' => '#fe932c'],
        'violet' => ['bg' => 'rgba(157, 93, 255, 0.1)', 'border' => '#9d5dff', 'icon' => '#d2bbff'],
    ];
    $colors = $colorMap[$data['color']] ?? $colorMap['emerald'];
@endphp

<div class="glass-tier-1 p-6 flex flex-col justify-between" 
     style="background: {{ $colors['bg'] }}; border-left: 4px solid {{ $colors['border'] }}">
    
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <p class="text-label-sm text-primary-low uppercase tracking-wide font-semibold">
                {{ $data['title'] }}
            </p>
        </div>
        @if($data['icon'])
        <div class="p-2 rounded-md" style="background: rgba(255, 255, 255, 0.5); color: {{ $colors['icon'] }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <!-- Icon will be rendered by Filament/Livewire -->
            </svg>
        </div>
        @endif
    </div>

    <!-- Value Section -->
    <div class="flex items-baseline gap-2 mb-3">
        <span class="display-md font-manrope font-bold" style="color: {{ $colors['border'] }}">
            {{ $data['value'] }}
        </span>
        @if($data['unit'])
        <span class="text-body-sm text-primary-medium">
            {{ $data['unit'] }}
        </span>
        @endif
    </div>

    <!-- Trend -->
    @if($data['showTrend'] && $data['trend'])
    <div class="flex items-center gap-2 pt-3 border-t border-outline-variant border-opacity-15">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            @if($data['trendDirection'] === 'up')
            <path d="M3 10a.75.75 0 01.75-.75h8.69L8.22 5.03a.75.75 0 111.06-1.06l5 5a.75.75 0 010 1.06l-5 5a.75.75 0 11-1.06-1.06l4.97-4.97H3.75A.75.75 0 013 10z" transform="rotate(-90)" style="color: #27d384"/>
            @elseif($data['trendDirection'] === 'down')
            <path d="M3 10a.75.75 0 01.75-.75h8.69L8.22 5.03a.75.75 0 111.06-1.06l5 5a.75.75 0 010 1.06l-5 5a.75.75 0 11-1.06-1.06l4.97-4.97H3.75A.75.75 0 013 10z" transform="rotate(90)" style="color: #c5192d"/>
            @else
            <path d="M10.75 2.75a.75.75 0 00-1.5 0v14.5a.75.75 0 001.5 0V2.75z" style="color: #cc7c1a"/>
            @endif
        </svg>
        <span class="text-label-sm font-medium" 
              style="color: @if($data['trendDirection'] === 'up') #27d384 @elseif($data['trendDirection'] === 'down') #c5192d @else #cc7c1a @endif">
            {{ $data['trend'] }}
        </span>
        <span class="text-label-sm text-primary-low">vs last period</span>
    </div>
    @endif
</div>
