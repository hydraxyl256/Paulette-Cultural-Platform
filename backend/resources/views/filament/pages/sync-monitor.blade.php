<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════
     SYNC MONITOR — Pixel-perfect · Dark / Light · Livewire
════════════════════════════════════════════════════════════ --}}

<style>
:root {
    --sm-card:        #ffffff;
    --sm-border:      rgba(228,228,231,0.85);
    --sm-border-in:   #f1f5f9;
    --sm-text-h:      #09090b;
    --sm-text-body:   #3f3f46;
    --sm-text-muted:  #71717a;
    --sm-text-subtle: #a1a1aa;
    --sm-surface:     #f8fafc;
    --sm-shadow:      0 1px 4px rgba(0,0,0,0.05);
    --sm-shadow-md:   0 4px 20px rgba(0,0,0,0.09);
}
.dark {
    --sm-card:        #1c1c27;
    --sm-border:      rgba(63,63,70,0.8);
    --sm-border-in:   rgba(39,39,42,0.9);
    --sm-text-h:      #f4f4f5;
    --sm-text-body:   #d4d4d8;
    --sm-text-muted:  #a1a1aa;
    --sm-text-subtle: #52525b;
    --sm-surface:     rgba(24,24,35,0.9);
    --sm-shadow:      0 1px 6px rgba(0,0,0,0.4);
    --sm-shadow-md:   0 4px 22px rgba(0,0,0,0.5);
}

.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }
.sm-root { font-family:'Inter','Manrope',system-ui,sans-serif; max-width:1320px; margin:0 auto; }

.sm-kpi:hover { transform:translateY(-3px); box-shadow:0 10px 32px rgba(0,0,0,0.12)!important; }
.sm-kpi { transition:transform .18s, box-shadow .18s; }
.sm-row:hover { background:var(--sm-surface)!important; }
.sm-btn:hover { transform:translateY(-1px); }
.sm-force-btn:hover { background:#047857!important; box-shadow:0 8px 28px rgba(5,150,105,.4)!important; }

/* Pulsing dot */
@keyframes sm-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.7)} }
.sm-pulse { animation:sm-pulse 1.4s ease-in-out infinite; display:inline-block; width:8px; height:8px; border-radius:50%; }

/* Pipeline connector bar */
.sm-connector { flex:1; height:5px; border-radius:99px; position:relative; overflow:hidden; }
.sm-connector-fill { position:absolute; inset:0; border-radius:99px; animation:sm-fill 2s ease-in-out infinite alternate; }
@keyframes sm-fill { from{width:60%} to{width:100%} }
</style>

<div class="sm-root">

{{-- ════ 1 · HEADER ════ --}}
<div style="margin-bottom:28px;">
    <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.16em; color:#059669; margin:0 0 10px;">INFRASTRUCTURE</p>
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 style="font-size:34px; font-weight:800; color:var(--sm-text-h); margin:0 0 8px; letter-spacing:-.04em;">Sync Monitor</h1>
            <p style="font-size:13px; color:var(--sm-text-muted); margin:0;">Real-time health telemetry for PCK global synchronization clusters.</p>
        </div>
        <div style="display:flex; gap:10px; margin-top:4px; flex-shrink:0;">
            <button type="button" wire:click="ckViewHistory" class="sm-btn" style="
                display:inline-flex; align-items:center; gap:8px;
                padding:10px 20px; border:1px solid var(--sm-border);
                border-radius:10px; background:var(--sm-card);
                color:var(--sm-text-body); font-size:13px; font-weight:600; cursor:pointer;
                box-shadow:var(--sm-shadow); transition:all .15s;
            ">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                View History
            </button>
            <button type="button" wire:click="ckForceSync" class="sm-force-btn" style="
                display:inline-flex; align-items:center; gap:8px;
                padding:10px 20px; border:none; border-radius:10px;
                background:#059669; color:#fff;
                font-size:13px; font-weight:700; cursor:pointer;
                box-shadow:0 4px 16px rgba(5,150,105,.3); transition:all .15s;
            ">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M21.5 2v6h-6"/><path d="M2.5 12a10 10 0 0 1 17.8-6.3L21.5 8"/>
                    <path d="M2.5 22v-6h6"/><path d="M21.5 12a10 10 0 0 1-17.8 6.3L2.5 16"/>
                </svg>
                Force Sync
            </button>
        </div>
    </div>
</div>

{{-- ════ 2 · KPI CARDS ════ --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px;">

    {{-- SUCCESS RATE --}}
    <div class="sm-kpi" style="background:var(--sm-card); border:1px solid var(--sm-border); border-radius:18px; padding:20px; box-shadow:var(--sm-shadow); position:relative; overflow:hidden;">
        <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; border-radius:50%; background:rgba(5,150,105,0.1); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
        </div>
        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--sm-text-subtle); margin:0 0 8px;">Success Rate</p>
        <div style="display:flex; align-items:baseline; gap:8px; margin-bottom:12px;">
            <span style="font-size:34px; font-weight:800; color:#059669; letter-spacing:-.04em;">{{ $metrics['success_rate'] }}%</span>
            <span style="font-size:12px; font-weight:700; color:#059669; background:rgba(5,150,105,0.1); padding:2px 8px; border-radius:20px;">+0.2%</span>
        </div>
        <div style="height:4px; background:var(--sm-border-in); border-radius:99px; overflow:hidden;">
            <div style="height:100%; width:{{ $metrics['success_rate'] }}%; background:#059669; border-radius:99px;"></div>
        </div>
    </div>

    {{-- AVG LATENCY --}}
    <div class="sm-kpi" style="background:var(--sm-card); border:1px solid var(--sm-border); border-radius:18px; padding:20px; box-shadow:var(--sm-shadow); position:relative; overflow:hidden;">
        <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; border-radius:50%; background:rgba(217,119,6,0.1); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--sm-text-subtle); margin:0 0 8px;">Avg Latency</p>
        <div style="display:flex; align-items:baseline; gap:8px; margin-bottom:12px;">
            <span style="font-size:34px; font-weight:800; color:#d97706; letter-spacing:-.04em;">{{ $metrics['avg_latency'] }}ms</span>
            <span style="font-size:11px; font-weight:600; color:var(--sm-text-subtle);">12ms</span>
        </div>
        <div style="height:4px; background:var(--sm-border-in); border-radius:99px; overflow:hidden;">
            <div style="height:100%; width:60%; background:linear-gradient(90deg,#d97706,#f59e0b); border-radius:99px;"></div>
        </div>
    </div>

    {{-- ACTIVE NODES --}}
    <div class="sm-kpi" style="background:var(--sm-card); border:1px solid var(--sm-border); border-radius:18px; padding:20px; box-shadow:var(--sm-shadow); position:relative; overflow:hidden;">
        <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; border-radius:50%; background:rgba(124,58,237,0.1); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.8">
                <circle cx="12" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/>
                <line x1="12" y1="7" x2="5" y2="17"/><line x1="12" y1="7" x2="19" y2="17"/><line x1="5" y1="19" x2="19" y2="19"/>
            </svg>
        </div>
        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--sm-text-subtle); margin:0 0 8px;">Active Nodes</p>
        <div style="display:flex; align-items:baseline; gap:8px; margin-bottom:12px;">
            <span style="font-size:34px; font-weight:800; color:#7c3aed; letter-spacing:-.04em;">{{ $metrics['active_nodes'] }}</span>
            <span style="font-size:11px; font-weight:700; color:#059669; background:rgba(5,150,105,0.08); padding:2px 8px; border-radius:20px;">Stable</span>
        </div>
        <div style="height:4px; background:var(--sm-border-in); border-radius:99px; overflow:hidden;">
            <div style="height:100%; width:100%; background:#7c3aed; border-radius:99px;"></div>
        </div>
    </div>

    {{-- BACKLOG --}}
    <div class="sm-kpi" style="background:var(--sm-card); border:1px solid var(--sm-border); border-radius:18px; padding:20px; box-shadow:var(--sm-shadow); position:relative; overflow:hidden;">
        <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; border-radius:50%; background:rgba(17,24,39,0.08); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sm-text-h)" stroke-width="1.8">
                <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
            </svg>
        </div>
        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--sm-text-subtle); margin:0 0 8px;">Backlog</p>
        <div style="display:flex; align-items:baseline; gap:8px; margin-bottom:12px;">
            <span style="font-size:34px; font-weight:800; color:var(--sm-text-h); letter-spacing:-.04em;">{{ $metrics['backlog'] }}</span>
            <span style="font-size:11px; font-weight:600; color:var(--sm-text-subtle);">Items</span>
        </div>
        <div style="height:4px; background:var(--sm-border-in); border-radius:99px; overflow:hidden;">
            <div style="height:100%; width:30%; background:var(--sm-text-h); border-radius:99px;"></div>
        </div>
    </div>

</div>

{{-- ════ 3 · LIVE PIPELINE VISUALIZATION ════ --}}
<div style="background:var(--sm-card); border:1px solid var(--sm-border); border-radius:20px; padding:28px; box-shadow:var(--sm-shadow); margin-bottom:22px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:32px;">
        <div style="width:32px; height:32px; border-radius:8px; background:rgba(5,150,105,0.1); display:flex; align-items:center; justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                <rect x="2" y="3" width="6" height="18" rx="1"/><rect x="9" y="3" width="6" height="18" rx="1"/><rect x="16" y="3" width="6" height="18" rx="1"/>
            </svg>
        </div>
        <h3 style="font-size:16px; font-weight:800; color:var(--sm-text-h); margin:0;">Live Pipeline Visualization</h3>
    </div>

    {{-- Pipeline nodes + connectors --}}
    <div style="display:flex; align-items:center; justify-content:center; gap:0; padding:0 20px;">

        @foreach ($stages as $i => $stage)

        {{-- Stage node --}}
        <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
            {{-- Circle --}}
            <div style="
                width:72px; height:72px; border-radius:50%;
                border:2.5px solid {{ $stage['color'] }};
                background:{{ $stage['bar'] ? 'rgba(' . implode(',', array_map('hexdec', str_split(ltrim($stage['color'],'#'),2))) . ',0.05)' : 'rgba(17,24,39,0.05)' }};
                display:flex; align-items:center; justify-content:center;
                margin-bottom:14px;
                box-shadow:0 0 0 6px {{ $stage['bar'] ? str_replace('0.3','0.08',$stage['bar_color']) : 'rgba(17,24,39,0.04)' }};
            ">
                {{-- Icon per stage --}}
                @if ($stage['icon'] === 'wifi')
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="{{ $stage['color'] }}" stroke-width="1.8" stroke-linecap="round">
                        <path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                        <circle cx="12" cy="20" r="1" fill="{{ $stage['color'] }}"/>
                    </svg>
                @elseif ($stage['icon'] === 'upload')
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="{{ $stage['color'] }}" stroke-width="1.8" stroke-linecap="round">
                        <polyline points="17 11 12 6 7 11"/><line x1="12" y1="6" x2="12" y2="18"/>
                        <polyline points="17 18 12 23 7 18"/>
                    </svg>
                @elseif ($stage['icon'] === 'sync')
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="{{ $stage['color'] }}" stroke-width="1.8" stroke-linecap="round">
                        <path d="M21.5 2v6h-6"/><path d="M21.34 15.57a10 10 0 1 1-.57-8.38"/>
                    </svg>
                @else
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--sm-text-h)" stroke-width="1.8" stroke-linecap="round">
                        <rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/>
                        <line x1="6" y1="6" x2="6.01" y2="6" stroke-width="2"/><line x1="6" y1="18" x2="6.01" y2="18" stroke-width="2"/>
                    </svg>
                @endif
            </div>

            {{-- Label --}}
            <p style="font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:{{ $stage['color'] }}; margin:0 0 4px; text-align:center;">{{ $stage['label'] }}</p>
            <p style="font-size:10px; font-weight:600; color:var(--sm-text-subtle); margin:0; text-align:center; letter-spacing:.04em;">{{ $stage['sub'] }}</p>
        </div>

        {{-- Connector bar (between nodes) --}}
        @if (!$loop->last)
        <div style="flex:1; height:5px; margin:0 12px; position:relative; background:var(--sm-border-in); border-radius:99px; overflow:hidden; margin-bottom:38px;">
            <div class="sm-connector-fill" style="position:absolute; top:0; left:0; height:100%; border-radius:99px;
                background:{{ $stage['bar_color'] }}; animation:sm-fill {{ 2 + $loop->index * 0.5 }}s ease-in-out infinite alternate;"></div>
        </div>
        @endif

        @endforeach
    </div>
</div>

{{-- ════ 4 · ACTIVE SYNC JOBS ════ --}}
<div style="background:var(--sm-card); border:1px solid var(--sm-border); border-radius:20px; padding:24px; box-shadow:var(--sm-shadow);">

    {{-- Table header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <h3 style="font-size:18px; font-weight:800; color:var(--sm-text-h); margin:0; letter-spacing:-.02em;">Active Sync Jobs</h3>
        <div style="display:flex; align-items:center; gap:10px;">
            {{-- Avatar group --}}
            <div style="display:flex;">
                @foreach (['JS'=>'#059669','MK'=>'#7c3aed','TL'=>'#d97706'] as $initials => $color)
                <div style="width:28px; height:28px; border-radius:50%; background:{{ $color }}; border:2px solid var(--sm-card); display:flex; align-items:center; justify-content:center; margin-left:-6px; first:margin-left:0;">
                    <span style="font-size:9px; font-weight:800; color:#fff; letter-spacing:0;">{{ $initials }}</span>
                </div>
                @endforeach
            </div>
            <span style="font-size:12px; font-weight:600; color:var(--sm-text-muted);">3 Monitoring Now</span>
        </div>
    </div>

    {{-- Column headers --}}
    <div style="display:grid; grid-template-columns:140px 1fr 180px 130px 80px 50px; gap:12px; padding:0 0 10px; border-bottom:1px solid var(--sm-border-in);">
        @foreach (['DEVICE ID','RESOURCE PATH','PROGRESS','STATUS','ETA','ACTION'] as $c)
        <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--sm-text-subtle);">{{ $c }}</span>
        @endforeach
    </div>

    {{-- Job rows --}}
    @foreach ($jobs as $job)
    @php $j = is_array($job) ? $job : $job->toArray(); @endphp
    <div class="sm-row" style="
        display:grid; grid-template-columns:140px 1fr 180px 130px 80px 50px;
        gap:12px; padding:16px 0; align-items:center;
        border-bottom:1px solid var(--sm-border-in);
        {{ $loop->last ? 'border-bottom:none;' : '' }}
        transition:background .12s;
    ">
        {{-- Device ID --}}
        <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:28px; height:28px; border-radius:6px; background:var(--sm-surface); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--sm-text-muted)" stroke-width="2">
                    <rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/>
                    <line x1="6" y1="6" x2="6.01" y2="6" stroke-width="2"/><line x1="6" y1="18" x2="6.01" y2="18" stroke-width="2"/>
                </svg>
            </div>
            <span style="font-size:12px; font-weight:700; color:var(--sm-text-h); font-family:monospace;">{{ $j['device_id'] }}</span>
        </div>

        {{-- Resource path --}}
        <p style="font-size:12px; color:var(--sm-text-muted); margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-family:monospace;">{{ $j['path'] }}</p>

        {{-- Progress --}}
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:4px; align-items:center;">
                <span style="font-size:11px; font-weight:700; color:var(--sm-text-h);">{{ $j['pct'] }}%</span>
                <span style="font-size:10px; font-weight:700; color:{{ $j['speed_color'] }};">{{ $j['speed'] }}</span>
            </div>
            <div style="height:5px; background:var(--sm-border-in); border-radius:99px; overflow:hidden;">
                <div style="height:100%; width:{{ $j['pct'] }}%; background:{{ $j['bar_color'] }}; border-radius:99px; transition:width .4s ease;"></div>
            </div>
        </div>

        {{-- Status badge --}}
        <div style="display:flex; align-items:center; gap:6px; padding:5px 12px; background:{{ $j['status_bg'] }}; border-radius:20px; width:fit-content;">
            @if ($j['status_icon'] === 'check')
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $j['status_color'] }}" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            @else
                <div class="sm-pulse" style="background:{{ $j['status_color'] }};"></div>
            @endif
            <span style="font-size:11px; font-weight:700; color:{{ $j['status_color'] }};">{{ $j['status'] }}</span>
        </div>

        {{-- ETA --}}
        <span style="font-size:12px; font-weight:600; color:var(--sm-text-muted);">{{ $j['eta'] }}</span>

        {{-- Action ⋮ --}}
        <button type="button" title="Job actions" style="width:28px; height:28px; border:none; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; border-radius:6px; transition:background .1s;" onmouseover="this.style.background='var(--sm-surface)'" onmouseout="this.style.background='transparent'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--sm-text-muted)" stroke="none">
                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
            </svg>
        </button>
    </div>
    @endforeach

    {{-- Load more --}}
    @if ($totalJobs > $ckJobsShown)
    <div style="text-align:center; padding:16px 0 4px;">
        <button type="button" wire:click="ckLoadMore" style="
            font-size:13px; font-weight:700; color:#059669;
            background:transparent; border:none; cursor:pointer;
            text-decoration:underline; text-underline-offset:3px;
        ">Load more jobs...</button>
    </div>
    @endif
</div>

</div>{{-- .sm-root --}}
</x-filament-panels::page>
