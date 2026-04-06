<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════
     QUEUE MANAGER  — Pixel-perfect · Dark / Light · Livewire
════════════════════════════════════════════════════════════ --}}

<style>
:root {
    --qm-card:        #ffffff;
    --qm-border:      rgba(228,228,231,0.85);
    --qm-border-in:   #f1f5f9;
    --qm-text-h:      #09090b;
    --qm-text-body:   #3f3f46;
    --qm-text-muted:  #71717a;
    --qm-text-subtle: #a1a1aa;
    --qm-surface:     #f8fafc;
    --qm-shadow:      0 1px 4px rgba(0,0,0,0.06);
    --qm-shadow-md:   0 4px 20px rgba(0,0,0,0.09);
    --qm-code-bg:     #f4f4f5;
}
.dark {
    --qm-card:        #1c1c27;
    --qm-border:      rgba(63,63,70,0.8);
    --qm-border-in:   rgba(39,39,42,0.9);
    --qm-text-h:      #f4f4f5;
    --qm-text-body:   #d4d4d8;
    --qm-text-muted:  #a1a1aa;
    --qm-text-subtle: #52525b;
    --qm-surface:     rgba(24,24,35,0.9);
    --qm-shadow:      0 1px 6px rgba(0,0,0,0.4);
    --qm-shadow-md:   0 4px 22px rgba(0,0,0,0.5);
    --qm-code-bg:     rgba(39,39,42,0.8);
}

.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

.qm-root { font-family:'Inter','Manrope',system-ui,sans-serif; max-width:1320px; margin:0 auto; }

/* Transitions */
.qm-kpi-card { transition:transform .18s, box-shadow .18s; }
.qm-kpi-card:hover { transform:translateY(-3px); box-shadow:0 10px 32px rgba(0,0,0,0.12)!important; }
.qm-job-row { transition:background .12s; }
.qm-job-row:hover { background:var(--qm-surface)!important; }
.qm-view-btn { cursor:pointer; transition:all .15s; }
.qm-action-btn { transition:transform .12s; }
.qm-action-btn:hover { transform:scale(1.12); }

/* Animated SVG wave */
@keyframes wave-shift { 0%{ transform:translateX(0); } 100%{ transform:translateX(-40px); } }
.qm-wave-primary { animation:wave-shift 4s linear infinite; }
.qm-wave-secondary { animation:wave-shift 6s linear infinite reverse; }

/* Pulse dot */
@keyframes qm-pulse { 0%,100%{ opacity:1; transform:scale(1); } 50%{ opacity:.5; transform:scale(.8); } }
.qm-pulse { animation:qm-pulse 1.5s ease-in-out infinite; }
</style>

<div class="qm-root">

{{-- ════ 1 · HEADER ════ --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:26px; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 style="font-size:32px; font-weight:800; color:var(--qm-text-h); margin:0 0 8px; letter-spacing:-.04em;">
            Queue Manager
        </h1>
        <p style="font-size:13px; color:var(--qm-text-muted); margin:0; line-height:1.6;">
            Monitoring orchestrator throughput and worker health across clusters.
        </p>
    </div>

    {{-- Live View / Historical toggle --}}
    <div style="display:flex; background:var(--qm-surface); border:1px solid var(--qm-border); border-radius:10px; padding:4px; gap:2px; flex-shrink:0; margin-top:4px;">
        <button type="button" wire:click="ckSetView('live')" class="qm-view-btn" style="
            padding:7px 18px; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer;
            background:{{ $ckViewMode === 'live' ? '#ffffff' : 'transparent' }};
            color:{{ $ckViewMode === 'live' ? '#09090b' : 'var(--qm-text-muted)' }};
            box-shadow:{{ $ckViewMode === 'live' ? '0 1px 4px rgba(0,0,0,0.1)' : 'none' }};
        ">Live View</button>
        <button type="button" wire:click="ckSetView('historical')" class="qm-view-btn" style="
            padding:7px 18px; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer;
            background:{{ $ckViewMode === 'historical' ? '#ffffff' : 'transparent' }};
            color:{{ $ckViewMode === 'historical' ? '#09090b' : 'var(--qm-text-muted)' }};
            box-shadow:{{ $ckViewMode === 'historical' ? '0 1px 4px rgba(0,0,0,0.1)' : 'none' }};
        ">Historical</button>
    </div>
</div>

{{-- ════ 2 · KPI CARDS ════ --}}
<div style="display:grid; grid-template-columns:repeat(6,1fr); gap:12px; margin-bottom:22px;">

    @php
    $kpis = [
        ['label'=>'PENDING',    'value'=>number_format($pending),  'icon_type'=>'refresh',   'color'=>'#3b82f6', 'bg'=>'rgba(59,130,246,0.10)'],
        ['label'=>'PROCESSING', 'value'=>$processing,              'icon_type'=>'spin',      'color'=>'#059669', 'bg'=>'rgba(5,150,105,0.10)'],
        ['label'=>'COMPLETED',  'value'=>$completed,               'icon_type'=>'check',     'color'=>'#059669', 'bg'=>'rgba(5,150,105,0.08)'],
        ['label'=>'FAILED',     'value'=>$failed,                  'icon_type'=>'alert',     'color'=>'#dc2626', 'bg'=>'rgba(220,38,38,0.10)'],
        ['label'=>'THROUGHPUT', 'value'=>$throughput,              'icon_type'=>'bolt',      'color'=>'#7c3aed', 'bg'=>'rgba(124,58,237,0.10)', 'suffix'=>'j/m'],
        ['label'=>'AVG TIME',   'value'=>$avgTime,                 'icon_type'=>'clock',     'color'=>'#d97706', 'bg'=>'rgba(217,119,6,0.10)',  'suffix'=>'min'],
    ];
    @endphp

    @foreach ($kpis as $k)
    <div class="qm-kpi-card" style="
        background:var(--qm-card); border:1px solid var(--qm-border);
        border-radius:14px; padding:16px 14px;
        box-shadow:var(--qm-shadow); position:relative; overflow:hidden;
    ">
        {{-- Accent circle --}}
        <div style="position:absolute; top:-10px; right:-10px; width:52px; height:52px; border-radius:50%; background:{{ $k['bg'] }};"></div>

        {{-- Icon --}}
        <div style="width:28px; height:28px; border-radius:8px; background:{{ $k['bg'] }}; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
            @if ($k['icon_type'] === 'refresh')
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $k['color'] }}" stroke-width="2.5"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.73-8.1L23 10"/></svg>
            @elseif ($k['icon_type'] === 'spin')
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $k['color'] }}" stroke-width="2.5"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
            @elseif ($k['icon_type'] === 'check')
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $k['color'] }}" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
            @elseif ($k['icon_type'] === 'alert')
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $k['color'] }}" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            @elseif ($k['icon_type'] === 'bolt')
                <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $k['color'] }}" stroke="none"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            @else
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $k['color'] }}" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            @endif
        </div>

        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--qm-text-subtle); margin:0 0 4px;">{{ $k['label'] }}</p>
        <div style="display:flex; align-items:baseline; gap:3px;">
            <span style="font-size:26px; font-weight:800; color:var(--qm-text-h); letter-spacing:-.04em; line-height:1;">{{ $k['value'] }}</span>
            @isset($k['suffix'])
                <span style="font-size:11px; font-weight:600; color:var(--qm-text-subtle);">{{ $k['suffix'] }}</span>
            @endisset
        </div>
    </div>
    @endforeach

</div>

{{-- ════ 3 · MIDDLE: Chart + Distribution ════ --}}
<div style="display:grid; grid-template-columns:1fr 320px; gap:18px; margin-bottom:22px;">

    {{-- Queue Depth Chart --}}
    <div style="background:var(--qm-card); border:1px solid var(--qm-border); border-radius:18px; padding:22px; box-shadow:var(--qm-shadow);">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:18px;">
            <div>
                <h3 style="font-size:15px; font-weight:800; color:var(--qm-text-h); margin:0 0 3px;">Queue Depth Over Time</h3>
                <p style="font-size:12px; color:var(--qm-text-muted); margin:0;">Real-time pressure analysis</p>
            </div>
            <div style="display:flex; gap:16px; align-items:center;">
                <div style="display:flex; align-items:center; gap:6px;">
                    <div class="qm-pulse" style="width:8px; height:8px; border-radius:50%; background:#059669;"></div>
                    <span style="font-size:11px; font-weight:600; color:var(--qm-text-muted);">Primary Cluster</span>
                </div>
                <div style="display:flex; align-items:center; gap:6px;">
                    <div style="width:8px; height:8px; border-radius:50%; background:#7c3aed;"></div>
                    <span style="font-size:11px; font-weight:600; color:var(--qm-text-muted);">Secondary Cluster</span>
                </div>
            </div>
        </div>

        {{-- SVG Wave Chart --}}
        <div style="overflow:hidden; border-radius:8px; position:relative; height:160px;">
            <svg viewBox="0 0 560 160" preserveAspectRatio="none" width="100%" height="160"
                 style="display:block; overflow:visible;">
                <defs>
                    <linearGradient id="gradGreen" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#059669" stop-opacity=".25"/>
                        <stop offset="100%" stop-color="#059669" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="gradPurple" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#7c3aed" stop-opacity=".2"/>
                        <stop offset="100%" stop-color="#7c3aed" stop-opacity="0"/>
                    </linearGradient>
                </defs>

                {{-- Grid lines --}}
                @foreach ([40,80,120] as $y)
                    <line x1="0" y1="{{ $y }}" x2="560" y2="{{ $y }}" stroke="var(--qm-border-in)" stroke-width="1"/>
                @endforeach

                {{-- Primary cluster fill --}}
                <path class="qm-wave-primary"
                    d="M-40 160 L-40 95 C0 70, 60 115, 100 90 S200 40, 240 65 S340 110, 380 72 S460 30, 500 55 S570 80, 600 65 L600 160 Z"
                    fill="url(#gradGreen)" opacity=".6"/>
                {{-- Primary cluster line --}}
                <path class="qm-wave-primary"
                    d="M-40 95 C0 70, 60 115, 100 90 S200 40, 240 65 S340 110, 380 72 S460 30, 500 55 S570 80, 600 65"
                    fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round"/>

                {{-- Secondary cluster fill --}}
                <path class="qm-wave-secondary"
                    d="M-40 160 L-40 115 C30 100, 80 130, 140 108 S230 75, 280 95 S360 125, 420 100 S490 75, 540 90 L600 85 L600 160 Z"
                    fill="url(#gradPurple)" opacity=".5"/>
                {{-- Secondary cluster line --}}
                <path class="qm-wave-secondary"
                    d="M-40 115 C30 100, 80 130, 140 108 S230 75, 280 95 S360 125, 420 100 S490 75, 540 90 L600 85"
                    fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
        </div>

        {{-- X-axis labels --}}
        <div style="display:flex; justify-content:space-between; padding:8px 0 0; border-top:1px solid var(--qm-border-in);">
            @foreach ($timeLabels as $t)
                <span style="font-size:11px; color:var(--qm-text-subtle); font-weight:500;">{{ $t }}</span>
            @endforeach
        </div>
    </div>

    {{-- Distribution + Auto-Scaling --}}
    <div style="display:flex; flex-direction:column; gap:14px;">

        {{-- Distribution --}}
        <div style="background:var(--qm-card); border:1px solid var(--qm-border); border-radius:18px; padding:20px; box-shadow:var(--qm-shadow); flex:1;">
            <h3 style="font-size:14px; font-weight:800; color:var(--qm-text-h); margin:0 0 3px;">Distribution</h3>
            <p style="font-size:11px; color:var(--qm-text-muted); margin:0 0 16px;">Job workload by category</p>

            @php
            $dist = [
                ['label'=>'Metadata Ingestion', 'pct'=>64, 'color'=>'#059669'],
                ['label'=>'Image Processing',   'pct'=>22, 'color'=>'#7c3aed'],
                ['label'=>'Audit Export',         'pct'=>14, 'color'=>'#f97316'],
            ];
            @endphp

            @foreach ($dist as $d)
            <div style="margin-bottom:13px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                    <span style="font-size:12px; font-weight:600; color:var(--qm-text-body);">{{ $d['label'] }}</span>
                    <span style="font-size:12px; font-weight:800; color:var(--qm-text-h);">{{ $d['pct'] }}%</span>
                </div>
                <div style="height:5px; background:var(--qm-border-in); border-radius:99px; overflow:hidden;">
                    <div style="height:100%; width:{{ $d['pct'] }}%; background:{{ $d['color'] }}; border-radius:99px; transition:width .6s ease;"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Auto-Scaling card --}}
        <div style="
            background:linear-gradient(135deg, rgba(5,150,105,0.08) 0%, var(--qm-card) 100%);
            border:1px solid rgba(5,150,105,0.25);
            border-radius:14px; padding:16px;
            display:flex; align-items:center; gap:12px;
        ">
            <div style="width:36px; height:36px; border-radius:10px; background:rgba(5,150,105,0.12); flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#059669" stroke="none">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
            </div>
            <div>
                <p style="font-size:13px; font-weight:800; color:var(--qm-text-h); margin:0 0 2px;">Auto-Scaling</p>
                <p style="font-size:11px; color:#059669; font-weight:600; margin:0;">System is performing optimally</p>
            </div>
        </div>
    </div>
</div>

{{-- ════ 4 · ACTIVE JOBS TABLE ════ --}}
<div style="background:var(--qm-card); border:1px solid var(--qm-border); border-radius:18px; padding:22px; box-shadow:var(--qm-shadow); margin-bottom:22px;">

    {{-- Table header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <h3 style="font-size:17px; font-weight:800; color:var(--qm-text-h); margin:0;">Active Jobs</h3>
        <div style="display:flex; gap:8px;">
            <button type="button" title="Filter" style="width:32px; height:32px; border:1px solid var(--qm-border); border-radius:8px; background:var(--qm-surface); display:flex; align-items:center; justify-content:center; cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--qm-text-muted)" stroke-width="2">
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="12" y1="18" x2="12" y2="18"/>
                </svg>
            </button>
            <button type="button" title="More" style="width:32px; height:32px; border:1px solid var(--qm-border); border-radius:8px; background:var(--qm-surface); display:flex; align-items:center; justify-content:center; cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--qm-text-muted)" stroke="none">
                    <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Column headers --}}
    <div style="display:grid; grid-template-columns:180px 1fr 120px 1fr 80px; gap:12px; padding:0 0 10px; border-bottom:1px solid var(--qm-border-in); margin-bottom:2px;">
        @foreach (['JOB ID','CATEGORY','PRIORITY','PROGRESS','ACTION'] as $col)
            <span style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--qm-text-subtle);">{{ $col }}</span>
        @endforeach
    </div>

    @foreach ($activeJobs as $job)
    @php
        $priColors = [
            'CRITICAL'=>['bg'=>'rgba(220,38,38,0.12)','text'=>'#dc2626'],
            'NORMAL'  =>['bg'=>'rgba(217,119,6,0.12)', 'text'=>'#d97706'],
            'LOW'     =>['bg'=>'rgba(5,150,105,0.12)',  'text'=>'#059669'],
        ];
        $pc = $priColors[$job['priority']] ?? ['bg'=>'rgba(100,100,100,0.1)','text'=>'#666'];
    @endphp
    <div class="qm-job-row" style="
        display:grid; grid-template-columns:180px 1fr 120px 1fr 80px;
        gap:12px; padding:14px 0; align-items:center;
        border-bottom:1px solid var(--qm-border-in);
        {{ $loop->last ? 'border-bottom:none;' : '' }}
    ">
        {{-- Job ID + worker --}}
        <div>
            <p style="font-size:13px; font-weight:700; color:var(--qm-text-h); margin:0; font-family:monospace;">{{ $job['id'] }}</p>
            <p style="font-size:11px; color:var(--qm-text-subtle); margin:2px 0 0;">Worker: {{ $job['worker'] }}</p>
        </div>

        {{-- Category --}}
        <p style="font-size:13px; font-weight:600; color:var(--qm-text-body); margin:0;">{{ $job['category'] }}</p>

        {{-- Priority badge --}}
        <span style="
            display:inline-block; padding:4px 12px;
            background:{{ $pc['bg'] }}; color:{{ $pc['text'] }};
            font-size:10px; font-weight:800; letter-spacing:.1em;
            border-radius:20px; text-transform:uppercase; width:fit-content;
        ">{{ $job['priority'] }}</span>

        {{-- Progress --}}
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                <span style="font-size:11px; color:var(--qm-text-muted);">{{ $job['status'] }}</span>
                <span style="font-size:11px; font-weight:700; color:var(--qm-text-h);">{{ $job['pct'] }}%</span>
            </div>
            <div style="height:5px; background:var(--qm-border-in); border-radius:99px; overflow:hidden;">
                <div style="height:100%; width:{{ $job['pct'] }}%; background:{{ $job['bar_color'] }}; border-radius:99px; transition:width .4s ease;"></div>
            </div>
        </div>

        {{-- Action button --}}
        <div style="display:flex; justify-content:flex-end;">
            <button type="button" class="qm-action-btn" style="
                width:32px; height:32px; border-radius:50%;
                border:1px solid var(--qm-border);
                background:var(--qm-surface);
                display:flex; align-items:center; justify-content:center; cursor:pointer;
            ">
                @if ($job['action'] === 'pause')
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="var(--qm-text-muted)" stroke="none">
                        <rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>
                    </svg>
                @else
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="var(--qm-text-muted)" stroke="none">
                        <polygon points="5 3 19 12 5 21 5 3"/>
                    </svg>
                @endif
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- ════ 5 · FAILED JOBS ALERT ════ --}}
<div style="background:var(--qm-card); border:1px solid rgba(220,38,38,0.2); border-radius:18px; padding:22px; box-shadow:var(--qm-shadow);">

    {{-- Alert header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px; gap:12px;">
        <div style="display:flex; align-items:flex-start; gap:14px;">
            <div style="width:38px; height:38px; border-radius:10px; background:rgba(220,38,38,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div>
                <h3 style="font-size:16px; font-weight:800; color:var(--qm-text-h); margin:0 0 4px;">Failed Jobs Requires Attention</h3>
                <p style="font-size:13px; color:var(--qm-text-muted); margin:0;">{{ $failed }} jobs have exited with error codes in the last 60 minutes.</p>
            </div>
        </div>
        <button type="button" wire:click="ckRetryAll" style="
            padding:10px 20px; background:#dc2626; color:#fff;
            border:none; border-radius:10px; cursor:pointer;
            font-size:13px; font-weight:700; white-space:nowrap; flex-shrink:0;
            box-shadow:0 4px 14px rgba(220,38,38,0.3);
            transition:all .15s;
        "
        onmouseover="this.style.background='#b91c1c'"
        onmouseout="this.style.background='#dc2626'">
            Retry All Failed
        </button>
    </div>

    {{-- Failed job rows --}}
    @foreach ($failedJobs as $f)
    <div style="
        background:{{ $loop->first ? 'rgba(220,38,38,0.04)' : 'rgba(217,119,6,0.04)' }};
        border:1px solid {{ $loop->first ? 'rgba(220,38,38,0.15)' : 'rgba(217,119,6,0.15)' }};
        border-radius:12px; padding:14px 16px; margin-bottom:10px;
        display:grid; grid-template-columns:1fr 1fr; gap:16px;
        {{ $loop->last ? 'margin-bottom:0;' : '' }}
    ">
        {{-- Left: error type + job ID --}}
        <div>
            <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:{{ $f['color'] }};">{{ $f['type'] }}</span>
            <p style="font-size:13px; font-weight:700; color:var(--qm-text-h); margin:4px 0 0; font-family:monospace;">
                {{ $f['id'] }} • {{ $f['name'] }}
            </p>
        </div>
        {{-- Right: stack trace --}}
        <div>
            <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--qm-text-subtle); margin:0 0 4px;">Stack Trace Snippet</p>
            <code style="font-size:11px; color:var(--qm-text-muted); background:var(--qm-code-bg); padding:4px 8px; border-radius:6px; display:block; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; font-family:monospace;">{{ $f['trace'] }}</code>
        </div>
    </div>
    @endforeach
</div>

{{-- ════ 6 · FOOTER ════ --}}
<div style="text-align:center; padding:24px 0 8px; border-top:1px solid var(--qm-border-in); margin-top:28px;">
    <p style="font-size:11px; color:var(--qm-text-subtle); margin:0;">
        © 2024 CuratorAdmin &bull; System Version 4.8.2-stable &bull; All systems operational.
    </p>
</div>

</div>{{-- .qm-root --}}
</x-filament-panels::page>
