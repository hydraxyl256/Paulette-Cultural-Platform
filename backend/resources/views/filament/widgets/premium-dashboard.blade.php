{{-- ═══════════════════════════════════════════════════════════════════
    COMMAND CENTER — Global Dashboard
    Pixel-perfect replica of the product designer's mockup.
    Sections: Header → Insight Banner → KPI Strip → Pipeline + Modules
              → Command Palette → Orgs Table + Activity Timeline → AI Card
═══════════════════════════════════════════════════════════════════ --}}

<div
    class="ck-god-dashboard mx-auto w-full max-w-[1440px] pb-10"
    wire:key="premium-dashboard-root"
    x-data="{
        showInsight: true,
        chartOn: true,
        dismissed: {}
    }"
    style="font-family: 'Inter', 'Manrope', system-ui, -apple-system, sans-serif;"
>
    {{-- ═══════════════════════════════════════════════════════════════
         1. HEADER — Command Center
    ═══════════════════════════════════════════════════════════════ --}}
    <header style="
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 8px 0 24px 0;
    ">
        <div>
            <h1 style="
                font-family: 'Manrope', 'Inter', system-ui, sans-serif;
                font-size: 28px;
                font-weight: 800;
                color: #18181b;
                margin: 0;
                letter-spacing: -0.02em;
            ">Command Center</h1>
            <p style="
                font-size: 13px;
                color: #71717a;
                margin: 4px 0 0 0;
                font-weight: 500;
            ">Real-time status of the Paulette Culture Kids ecosystem.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a
                href="{{ $auditUrl }}"
                wire:navigate
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 10px 18px;
                    border: 1px solid #e4e4e7;
                    border-radius: 12px;
                    background: #fff;
                    color: #3f3f46;
                    font-size: 13px;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.2s;
                    cursor: pointer;
                "
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                System Export
            </a>
            <button
                type="button"
                wire:click="$refresh"
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 12px;
                    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
                    color: #fff;
                    font-size: 13px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all 0.2s;
                    box-shadow: 0 4px 14px rgba(220,38,38,0.3);
                "
            >
                🚀 Deploy Update
            </button>
        </div>
    </header>

    {{-- ═══════════════════════════════════════════════════════════════
         2. SMART INSIGHT BANNER
    ═══════════════════════════════════════════════════════════════ --}}
    <div
        x-show="showInsight"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 -translate-y-2"
        style="
            background: linear-gradient(135deg, #059669 0%, #10b981 40%, #34d399 100%);
            border-radius: 16px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(5,150,105,0.25);
            position: relative;
        "
    >
        {{-- Shield icon --}}
        <div style="
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        ">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <p style="font-size: 14px; font-weight: 700; color: #fff; margin: 0;">Smart Insight: Optimization Available</p>
            <p style="font-size: 12px; color: rgba(255,255,255,0.85); margin: 3px 0 0 0; line-height: 1.4;">Cache utilization is at 94%. We recommend scaling the Comic Asset Pipeline to reduce latency in Region A.</p>
        </div>
        <button
            type="button"
            style="
                padding: 8px 16px;
                background: rgba(255,255,255,0.2);
                border: 1px solid rgba(255,255,255,0.3);
                border-radius: 10px;
                color: #fff;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                white-space: nowrap;
                transition: background 0.2s;
            "
        >Apply Fix</button>
        <button
            type="button"
            @click="showInsight = false"
            style="
                width: 30px; height: 30px;
                display: flex; align-items: center; justify-content: center;
                background: rgba(255,255,255,0.15);
                border: none;
                border-radius: 8px;
                color: #fff;
                font-size: 18px;
                cursor: pointer;
                flex-shrink: 0;
                transition: background 0.2s;
            "
        >×</button>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         3. KPI STRIP — 6 compact metric cards
    ═══════════════════════════════════════════════════════════════ --}}
    <section aria-label="Key metrics" style="margin-bottom: 28px;">
        <div class="ck-kpi-strip" style="
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 14px;
        ">
            @foreach ($kpis as $index => $kpi)
                <x-kpi-card
                    :label="$kpi['label']"
                    :value="$kpi['value']"
                    :trend="$kpi['trend']"
                    :trend-direction="$kpi['trendDirection']"
                    :meta="$kpi['meta']"
                    :bar-color="$kpi['barColor']"
                    :bar-width="$kpi['barWidth']"
                    style="animation: ckFadeUp 0.35s ease-out {{ $index * 50 }}ms both;"
                />
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         4. SYSTEM HEALTH PIPELINE + MODULE SWITCHES (Two-column)
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
        margin-bottom: 28px;
    " class="ck-pipeline-modules-grid">

        {{-- LEFT: System Health Pipeline --}}
        <div style="
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(228,228,231,0.5);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        ">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                <h2 style="font-family: 'Manrope', sans-serif; font-size: 18px; font-weight: 800; color: #18181b; margin: 0;">System Health Pipeline</h2>
                <span style="
                    display: inline-flex; align-items: center; gap: 6px;
                    font-size: 11px; font-weight: 600; color: #059669;
                ">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                    All Systems Operational
                </span>
            </div>

            {{-- Pipeline steps --}}
            <div style="display: flex; align-items: center; gap: 0; justify-content: space-between;">
                @php
                    $pipelineSteps = $health['pipelineSteps'] ?? [];
                    $stepNames = ['Detect', 'Validate', 'Apply', 'Confirm', 'Archive'];
                @endphp
                @foreach ($stepNames as $sIdx => $stepName)
                    @php
                        $step = $pipelineSteps[$sIdx] ?? [];
                        $status = $step['status'] ?? 'pending';
                        $isActive = $status === 'active';
                        $isDone = $status === 'done';

                        if ($isActive) {
                            $bgColor = 'linear-gradient(135deg, #006948 0%, #0f9361 50%, #27d384 100%)';
                            $iconColor = '#fff';
                            $borderColor = '#10b981';
                            $labelStyle = 'color: #059669; font-weight: 700;';
                        } elseif ($isDone) {
                            $bgColor = 'rgba(240,253,244,1)';
                            $iconColor = '#059669';
                            $borderColor = '#bbf7d0';
                            $labelStyle = 'color: #6b7280; font-weight: 600;';
                        } else {
                            $bgColor = '#f4f4f5';
                            $iconColor = '#a1a1aa';
                            $borderColor = '#e4e4e7';
                            $labelStyle = 'color: #a1a1aa; font-weight: 600;';
                        }
                    @endphp
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px; flex: 1;">
                        <div style="
                            width: {{ $isActive ? '52px' : '44px' }};
                            height: {{ $isActive ? '52px' : '44px' }};
                            border-radius: {{ $isActive ? '16px' : '14px' }};
                            background: {{ $bgColor }};
                            border: 2px solid {{ $borderColor }};
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            {{ $isActive ? 'box-shadow: 0 6px 20px rgba(16,185,129,0.35);' : '' }}
                            transition: all 0.3s;
                        ">
                            @if ($isDone)
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            @elseif ($isActive)
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            @else
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                            @endif
                        </div>
                        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; {{ $labelStyle }}">{{ $stepName }}</span>
                    </div>
                    @if ($sIdx < count($stepNames) - 1)
                        <div style="width: 24px; height: 2px; background: {{ $isDone ? '#bbf7d0' : '#e4e4e7' }}; margin-bottom: 22px; flex-shrink: 0;"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Module Switches --}}
        <div style="
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(228,228,231,0.5);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        ">
            <h2 style="font-family: 'Manrope', sans-serif; font-size: 18px; font-weight: 800; color: #18181b; margin: 0 0 20px 0;">Module Switches</h2>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach ($moduleDefs as $mod)
                    @php $on = $moduleToggles[$mod['key']] ?? false; @endphp
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1;">
                            <span style="
                                width: 36px; height: 36px;
                                border-radius: 10px;
                                background: {{ $on ? 'linear-gradient(135deg, #7c3aed, #6d28d9)' : '#f4f4f5' }};
                                display: flex; align-items: center; justify-content: center;
                                font-size: 16px; flex-shrink: 0;
                                {{ $on ? 'box-shadow: 0 2px 8px rgba(124,58,237,0.25);' : '' }}
                            ">{{ $mod['emoji'] }}</span>
                            <div style="min-width: 0;">
                                <p style="font-size: 13px; font-weight: 700; color: #18181b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $mod['label'] }}</p>
                                <p style="font-size: 10px; color: #a1a1aa; margin: 2px 0 0 0; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                                    {{ $on ? ($mod['key'] === 'comics' ? 'V 2.4.0 LIVE' : 'OPTIMIZATION ACTIVE') : 'OFFLINE' }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="toggleModule('{{ $mod['key'] }}')"
                            wire:key="mod-toggle-{{ $mod['key'] }}"
                            role="switch"
                            aria-checked="{{ $on ? 'true' : 'false' }}"
                            style="
                                position: relative;
                                width: 46px; height: 26px;
                                border-radius: 13px;
                                border: none;
                                background: {{ $on ? 'linear-gradient(135deg, #059669, #10b981)' : '#d4d4d8' }};
                                cursor: pointer;
                                transition: background 0.3s;
                                flex-shrink: 0;
                                {{ $on ? 'box-shadow: 0 0 10px rgba(16,185,129,0.3);' : '' }}
                            "
                        >
                            <span style="
                                position: absolute;
                                top: 3px;
                                left: {{ $on ? '23px' : '3px' }};
                                width: 20px; height: 20px;
                                border-radius: 50%;
                                background: #fff;
                                box-shadow: 0 1px 4px rgba(0,0,0,0.15);
                                transition: left 0.2s ease;
                            "></span>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         5. COMMAND PALETTE HINT
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        display: flex;
        justify-content: center;
        margin-bottom: 28px;
    ">
        <div style="
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: #27272a;
            border-radius: 24px;
            color: #a1a1aa;
            font-size: 12px;
            font-weight: 500;
        ">
            Press
            <kbd style="
                display: inline-flex; align-items: center; gap: 3px;
                padding: 2px 6px;
                background: #3f3f46;
                border-radius: 5px;
                font-size: 11px;
                font-weight: 700;
                color: #d4d4d8;
                font-family: system-ui, monospace;
            ">⌘ K</kbd>
            to open command palette
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         6. ACTIVE ORGANISATIONS + ACTIVITY TIMELINE (Two-column)
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        margin-bottom: 28px;
    " class="ck-orgs-activity-grid">

        {{-- LEFT: Active Organisations --}}
        <div style="
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(228,228,231,0.5);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        ">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h2 style="font-family: 'Manrope', sans-serif; font-size: 18px; font-weight: 800; color: #18181b; margin: 0;">Active Organisations</h2>
                <a
                    href="{{ \App\Filament\Resources\OrganisationResource::getUrl('index') }}"
                    wire:navigate
                    style="font-size: 12px; font-weight: 600; color: #059669; text-decoration: none;"
                >View All Registry</a>
            </div>

            {{-- Table header --}}
            <div style="
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1fr;
                gap: 8px;
                padding: 10px 12px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: #a1a1aa;
                border-bottom: 1px solid #f4f4f5;
            ">
                <span>Organisation</span>
                <span>Region</span>
                <span>Total Users</span>
                <span>Status</span>
            </div>

            {{-- Table rows --}}
            @foreach (array_slice($organisationsTable, 0, 5) as $row)
                @php
                    $statusBg = match ($row['plan_tone']) {
                        'emerald' => 'background: #059669; color: #fff;',
                        'amber' => 'background: #d97706; color: #fff;',
                        default => 'background: #7c3aed; color: #fff;',
                    };
                    $regions = ['West Africa', 'East Africa', 'Global / EU', 'South Africa', 'Central Africa'];
                @endphp
                <div style="
                    display: grid;
                    grid-template-columns: 2fr 1fr 1fr 1fr;
                    gap: 8px;
                    padding: 14px 12px;
                    align-items: center;
                    border-bottom: 1px solid #fafafa;
                    transition: background 0.2s;
                " class="ck-org-row-hover">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="
                            width: 32px; height: 32px;
                            border-radius: 10px;
                            background: linear-gradient(135deg, {{ $row['plan_tone'] === 'emerald' ? '#059669, #10b981' : ($row['plan_tone'] === 'amber' ? '#d97706, #f59e0b' : '#7c3aed, #a78bfa') }});
                            display: flex; align-items: center; justify-content: center;
                            font-size: 14px; color: #fff; flex-shrink: 0;
                        ">{{ mb_strtoupper(mb_substr($row['name'], 0, 1)) }}</div>
                        <span style="font-size: 13px; font-weight: 600; color: #18181b;">{{ $row['name'] }}</span>
                    </div>
                    <span style="font-size: 12px; color: #71717a;">{{ $regions[$loop->index % count($regions)] }}</span>
                    <span style="font-size: 13px; font-weight: 700; color: #18181b;">{{ number_format($row['children'] * 84) }}</span>
                    <span style="
                        display: inline-block;
                        padding: 4px 10px;
                        border-radius: 6px;
                        font-size: 10px;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 0.04em;
                        {{ $statusBg }}
                        width: fit-content;
                    ">{{ $row['plan_label'] }}</span>
                </div>
            @endforeach
        </div>

        {{-- RIGHT: Activity Timeline --}}
        <div style="
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(228,228,231,0.5);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        ">
            <h2 style="font-family: 'Manrope', sans-serif; font-size: 18px; font-weight: 800; color: #18181b; margin: 0 0 20px 0;">Activity Timeline</h2>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach (array_slice($activities, 0, 4) as $item)
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        {{-- Avatar --}}
                        <div style="
                            width: 38px; height: 38px;
                            border-radius: 50%;
                            background: linear-gradient(135deg, {{ $item['tone'] === 'emerald' ? '#059669, #34d399' : ($item['tone'] === 'amber' ? '#d97706, #fbbf24' : '#7c3aed, #a78bfa') }});
                            display: flex; align-items: center; justify-content: center;
                            color: #fff; font-size: 12px; font-weight: 700;
                            flex-shrink: 0;
                        ">{{ $item['avatar'] }}</div>
                        <div style="min-width: 0; flex: 1;">
                            <p style="font-size: 13px; color: #3f3f46; margin: 0; line-height: 1.5;">
                                <span style="font-weight: 700; color: #18181b;">{{ $item['actor'] }}</span>
                                {{ Str::limit($item['line'], 40) }}
                            </p>
                            <p style="font-size: 11px; color: #a1a1aa; margin: 4px 0 0 0; text-transform: uppercase; letter-spacing: 0.03em; font-weight: 500;">{{ $item['time'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         7. DIGITAL CURATOR AI — AI Insight Card
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="display: flex; justify-content: flex-end;">
        <div style="
            width: 360px;
            background: linear-gradient(160deg, #f0fdf4 0%, #ecfdf5 50%, #f0fdf4 100%);
            border: 1px solid #bbf7d0;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(16,185,129,0.1);
        ">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                <div style="
                    width: 28px; height: 28px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #059669, #10b981);
                    display: flex; align-items: center; justify-content: center;
                ">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                </div>
                <span style="font-size: 14px; font-weight: 700; color: #059669;">Digital Curator AI</span>
            </div>
            <p style="font-size: 13px; color: #3f3f46; line-height: 1.6; margin: 0 0 16px 0;">
                User engagement is peaking in the "Cultural Heritage" category. High potential for a new bundle launch in the East Africa region based on current comic consumption trends.
            </p>
            <button
                type="button"
                style="
                    display: block;
                    width: 100%;
                    padding: 10px 0;
                    background: transparent;
                    border: 2px solid #dc2626;
                    border-radius: 24px;
                    color: #dc2626;
                    font-size: 13px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all 0.2s;
                "
            >Generate Strategy Report</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     RESPONSIVE + ANIMATION STYLES
═══════════════════════════════════════════════════════════════ --}}
<style>
    @keyframes ckFadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    /* KPI responsive */
    @media (max-width: 768px) {
        .ck-kpi-strip {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }
        .ck-pipeline-modules-grid {
            grid-template-columns: 1fr !important;
        }
        .ck-orgs-activity-grid {
            grid-template-columns: 1fr !important;
        }
    }
    @media (min-width: 769px) and (max-width: 1024px) {
        .ck-kpi-strip {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 12px !important;
        }
    }
    /* Org row hover */
    .ck-org-row-hover:hover {
        background: rgba(241,245,249,0.6);
    }
    /* Scrollbar */
    .ck-god-dashboard ::-webkit-scrollbar { width: 5px; }
    .ck-god-dashboard ::-webkit-scrollbar-thumb {
        background: rgba(16,185,129,0.3);
        border-radius: 999px;
    }
</style>
