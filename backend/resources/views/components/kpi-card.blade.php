{{--
    KPI Card — pixel-perfect match to Command Center design.

    @props label, value, trend, trendDirection, meta, barColor, barWidth
--}}
@props([
    'label' => '',
    'value' => '',
    'trend' => '',
    'trendDirection' => 'neutral',
    'meta' => '',
    'barColor' => 'emerald',
    'barWidth' => 60,
])

@php
    $trendClasses = match ($trendDirection) {
        'up'      => 'color: #059669;',
        'down'    => 'color: #e11d48;',
        'warning' => 'color: #d97706;',
        default   => 'color: #9ca3af;',
    };

    $barBg = match ($barColor) {
        'amber'   => 'background: linear-gradient(90deg, #fbbf24, #f97316, #f59e0b);',
        default   => 'background: linear-gradient(90deg, #34d399, #10b981, #059669);',
    };
@endphp

<div {{ $attributes->merge(['class' => 'ck-kpi-card']) }}
     style="
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        border-radius: 16px;
        border: 1px solid rgba(228,228,231,0.6);
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 0 0 1px rgba(0,0,0,0.015);
        transition: all 0.3s ease;
        cursor: default;
     ">
    {{-- Card content --}}
    <div style="padding: 16px 18px 4px 18px; flex: 1; display: flex; flex-direction: column; justify-content: center;">
        {{-- Label --}}
        <p style="
            margin: 0;
            font-family: 'Inter', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #a1a1aa;
            line-height: 1;
        ">{{ $label }}</p>

        {{-- Value + Trend --}}
        <div style="margin-top: 10px; display: flex; align-items: baseline; gap: 6px;">
            <span style="
                font-family: 'Manrope', 'Inter', system-ui, -apple-system, sans-serif;
                font-size: 26px;
                font-weight: 800;
                line-height: 1;
                letter-spacing: -0.02em;
                color: #18181b;
            ">{{ $value }}</span>
            @if ($trend)
                <span style="
                    font-family: 'Inter', system-ui, sans-serif;
                    font-size: 11px;
                    font-weight: 700;
                    white-space: nowrap;
                    {{ $trendClasses }}
                ">{{ $trend }}</span>
            @endif
        </div>

        {{-- Meta --}}
        @if ($meta)
            <p style="
                margin: 4px 0 0 0;
                font-family: 'Inter', system-ui, sans-serif;
                font-size: 10px;
                font-weight: 500;
                color: #a1a1aa;
                line-height: 1.3;
            ">{{ $meta }}</p>
        @endif
    </div>

    {{-- Bottom gradient bar --}}
    <div style="padding: 8px 14px 14px 14px;">
        <div style="
            height: 4px;
            width: 100%;
            border-radius: 999px;
            background: #f4f4f5;
            overflow: hidden;
        ">
            <div style="
                height: 100%;
                border-radius: 999px;
                width: {{ min(max($barWidth, 0), 100) }}%;
                {{ $barBg }}
                transition: width 0.7s ease;
            "></div>
        </div>
    </div>
</div>
