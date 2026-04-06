@props(['icon' => '📊', 'label', 'value', 'trend' => null, 'trendType' => 'positive'])

<div class="bg-white border border-slate-200 rounded-lg shadow-sm hover:shadow-md transition p-6">
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="text-sm font-medium text-slate-600">{{ $label }}</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $value }}</p>
            @if($trend)
                <p class="text-xs mt-2 @if($trendType === 'positive') text-green-600 @else text-red-600 @endif">
                    @if($trendType === 'positive') ↑ @else ↓ @endif {{ $trend }}
                </p>
            @endif
        </div>
        <div class="text-3xl">{{ $icon }}</div>
    </div>
</div>
