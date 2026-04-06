<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════════
     AGE PROFILES — Pixel-perfect · Dark / Light mode
     Layout: Header → 4 Profile Cards → Bottom (Audience Growth + Dark card | Activity Feed)
════════════════════════════════════════════════════════════════════ --}}

<style>
/* ── Light tokens ── */
:root {
    --ap-card:         #ffffff;
    --ap-border:       rgba(228,228,231,0.8);
    --ap-border-inner: #f1f5f9;
    --ap-text-h:       #09090b;
    --ap-text-body:    #3f3f46;
    --ap-text-muted:   #71717a;
    --ap-text-subtle:  #a1a1aa;
    --ap-surface:      #f8fafc;
    --ap-shadow-sm:    0 1px 4px rgba(0,0,0,0.05);
    --ap-shadow-md:    0 4px 18px rgba(0,0,0,0.08);
    --ap-row-hover:    #f8fafc;
}
.dark {
    --ap-card:         #1c1c27;
    --ap-border:       rgba(63,63,70,0.85);
    --ap-border-inner: rgba(39,39,42,0.9);
    --ap-text-h:       #f4f4f5;
    --ap-text-body:    #d4d4d8;
    --ap-text-muted:   #a1a1aa;
    --ap-text-subtle:  #52525b;
    --ap-surface:      rgba(24,24,35,0.9);
    --ap-shadow-sm:    0 1px 6px rgba(0,0,0,0.4);
    --ap-shadow-md:    0 4px 20px rgba(0,0,0,0.45);
    --ap-row-hover:    #22222f;
}

/* suppress Filament default header */
.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

.ap-root { font-family:'Inter','Manrope',system-ui,sans-serif; max-width:1320px; margin:0 auto; }

/* Hover & transition */
.ap-profile-card { transition:transform .18s, box-shadow .18s; cursor:pointer; }
.ap-profile-card:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.13) !important; }
.ap-btn:hover { transform:translateY(-1px); }
.ap-arrow-btn:hover { transform:scale(1.1); }
.ap-create-btn:hover { background:#047857!important; transform:translateY(-2px); box-shadow:0 8px 28px rgba(5,150,105,.4)!important; }
.ap-search:focus { border-color:#059669!important; box-shadow:0 0 0 3px rgba(5,150,105,.12)!important; outline:none; }
</style>

<div class="ap-root">

{{-- ════ 1 · HEADER ════ --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; gap:20px; flex-wrap:wrap;">
    <div>
        <h1 style="font-size:36px; font-weight:800; color:var(--ap-text-h); margin:0 0 10px; letter-spacing:-.045em; line-height:1.05;">
            Age Profiles
        </h1>
        <p style="font-size:13px; color:var(--ap-text-muted); margin:0; max-width:520px; line-height:1.65;">
            Configure and manage demographic parameters, UI personality modes,<br>
            and cognitive difficulty tiers for curated experiences.
        </p>
    </div>
    <a href="{{ $createUrl }}" wire:navigate class="ap-create-btn" style="
        display:inline-flex; align-items:center; gap:9px;
        padding:12px 24px;
        background:#059669; color:#fff;
        font-size:13px; font-weight:700;
        border-radius:50px; text-decoration:none;
        box-shadow:0 4px 18px rgba(5,150,105,.3);
        transition:all .2s; flex-shrink:0;
    ">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        Create Age Profile
    </a>
</div>

{{-- ════ 2 · FOUR PROFILE CARDS ════ --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:28px;">

@foreach ($profiles as $p)
@php
    $color   = $p['color'];
    $accent  = $p['accent'];
    $barClr  = $p['bar_color'];
    $barPct  = $p['bar_pct'];
@endphp
<div class="ap-profile-card" style="
    background:var(--ap-card);
    border:1px solid var(--ap-border);
    border-radius:20px;
    padding:22px 20px 20px;
    box-shadow:var(--ap-shadow-md);
    position:relative; overflow:hidden;
">
    {{-- Decorative bg icon --}}
    <div style="
        position:absolute; right:-14px; top:-10px;
        width:90px; height:90px; border-radius:50%;
        background:{{ $accent }};
        display:flex; align-items:center; justify-content:center;
    ">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
             stroke="{{ $color }}" stroke-width="1.2" opacity=".5"
             stroke-linecap="round" stroke-linejoin="round">
            {!! $p['icon_svg'] !!}
        </svg>
    </div>

    {{-- Badge --}}
    <div style="margin-bottom:10px;">
        <span style="
            display:inline-block; padding:4px 11px;
            background:{{ $accent }}; color:{{ $color }};
            font-size:9px; font-weight:800; letter-spacing:.14em;
            border-radius:20px; text-transform:uppercase;
        ">{{ $p['badge'] }}</span>
    </div>

    {{-- Age range --}}
    <p style="font-size:40px; font-weight:800; color:var(--ap-text-h); margin:0 0 14px; letter-spacing:-.04em; line-height:1;">
        {{ $p['ageRange'] }}
    </p>

    {{-- Dev stage label --}}
    <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:var(--ap-text-subtle); margin:0 0 4px;">
        Development Stage
    </p>
    <p style="font-size:14px; font-weight:700; color:var(--ap-text-h); margin:0 0 14px; line-height:1.35;">
        {{ $p['stage'] }}
    </p>

    {{-- UI Mode chip --}}
    <div style="display:inline-flex; align-items:center; gap:7px; padding:5px 12px; background:{{ $accent }}; border-radius:20px; margin-bottom:12px;">
        <span style="font-size:14px;">{{ $p['ui_icon'] }}</span>
        <span style="font-size:12px; font-weight:700; color:{{ $color }};">UI: {{ $p['ui_label'] }}</span>
    </div>

    {{-- Progress bar --}}
    <div style="height:4px; background:var(--ap-border-inner); border-radius:99px; overflow:hidden; margin-bottom:8px;">
        <div style="height:100%; width:{{ $barPct }}%; background:{{ $barClr }}; border-radius:99px;"></div>
    </div>

    {{-- Difficulty --}}
    <div style="display:flex; justify-content:space-between; margin-bottom:18px;">
        <span style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ap-text-subtle);">
            Difficulty: {{ $p['diff_label'] }}
        </span>
        <span style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ap-text-subtle);">
            Tier {{ $p['diff_tier'] }}
        </span>
    </div>

    {{-- Enrollment + arrow --}}
    <div style="border-top:1px solid var(--ap-border-inner); padding-top:14px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <p style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ap-text-subtle); margin:0 0 3px;">Enrollment</p>
            <p style="font-size:22px; font-weight:800; color:var(--ap-text-h); margin:0; letter-spacing:-.03em;">{{ $p['enrollment'] }}</p>
        </div>
        <a href="{{ $p['editUrl'] }}" wire:navigate class="ap-arrow-btn" style="
            width:36px; height:36px; border-radius:50%; border:1px solid var(--ap-border);
            display:flex; align-items:center; justify-content:center;
            background:var(--ap-surface); text-decoration:none;
            transition:all .15s;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="var(--ap-text-muted)" stroke-width="2.5" stroke-linecap="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
    </div>
</div>
@endforeach

</div>

{{-- ════ 3 · BOTTOM TWO COLUMNS ════ --}}
<div style="display:grid; grid-template-columns:300px 1fr; gap:18px;">

    {{-- ── LEFT column: Audience Growth + Update card ── --}}
    <div style="display:flex; flex-direction:column; gap:18px;">

        {{-- Audience Growth --}}
        <div style="
            background:var(--ap-card); border:1px solid var(--ap-border);
            border-radius:20px; padding:22px;
            box-shadow:var(--ap-shadow-sm);
        ">
            <h3 style="font-size:16px; font-weight:800; color:var(--ap-text-h); margin:0 0 18px; letter-spacing:-.02em;">
                Audience Growth
            </h3>

            {{-- Total Enrolled --}}
            <div style="display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid var(--ap-border-inner);">
                <div style="
                    width:40px; height:40px; border-radius:12px; flex-shrink:0;
                    background:rgba(5,150,105,0.12);
                    display:flex; align-items:center; justify-content:center;
                ">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <p style="font-size:12px; font-weight:600; color:var(--ap-text-muted); margin:0;">Total Enrolled</p>
                    <p style="font-size:10px; color:var(--ap-text-subtle); margin:2px 0 0;">Across all profiles</p>
                </div>
                <span style="font-size:20px; font-weight:800; color:var(--ap-text-h); letter-spacing:-.03em;">{{ $totalEnrolled }}</span>
            </div>

            {{-- Top Performing --}}
            <div style="display:flex; align-items:center; gap:14px; padding:12px 0 0;">
                <div style="
                    width:40px; height:40px; border-radius:12px; flex-shrink:0;
                    background:rgba(124,58,237,0.10);
                    display:flex; align-items:center; justify-content:center;
                ">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <p style="font-size:12px; font-weight:600; color:var(--ap-text-muted); margin:0;">Top Performing</p>
                    <p style="font-size:10px; color:var(--ap-text-subtle); margin:2px 0 0;">8-12 Year Olds</p>
                </div>
                <span style="font-size:20px; font-weight:800; color:var(--ap-text-h); letter-spacing:-.03em;">31%</span>
            </div>
        </div>

        {{-- Dark update card --}}
        <div style="
            background:linear-gradient(160deg,#052e16 0%,#064e3b 50%,#0d1117 100%);
            border-radius:20px; padding:24px;
            box-shadow:0 6px 28px rgba(0,0,0,0.35);
            position:relative; overflow:hidden; min-height:160px;
        ">
            {{-- Decorative circles --}}
            <div style="position:absolute; top:-30px; right:-30px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.04);"></div>
            <div style="position:absolute; bottom:-20px; left:-20px; width:80px; height:80px; border-radius:50%; background:rgba(5,150,105,0.08);"></div>

            {{-- 3D icon --}}
            <div style="margin-bottom:14px;">
                <div style="width:52px; height:52px; border-radius:14px; background:rgba(5,150,105,0.2); display:flex; align-items:center; justify-content:center;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
            </div>
            <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.18em; color:rgba(52,211,153,0.7); margin:0 0 8px;">New Update</p>
            <p style="font-size:18px; font-weight:800; color:#ffffff; margin:0; line-height:1.3; letter-spacing:-.02em;">
                Adaptive Learning<br>Algorithms v2.4
            </p>
        </div>
    </div>

    {{-- ── RIGHT column: Recent Configuration Changes ── --}}
    <div style="
        background:var(--ap-card); border:1px solid var(--ap-border);
        border-radius:20px; padding:24px;
        box-shadow:var(--ap-shadow-sm);
    ">
        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px;">
            <h3 style="font-size:17px; font-weight:800; color:var(--ap-text-h); margin:0; letter-spacing:-.02em;">
                Recent Configuration Changes
            </h3>
            <a href="{{ $auditUrl }}" wire:navigate style="
                display:inline-flex; align-items:center; gap:5px;
                font-size:12px; font-weight:700; color:#059669; text-decoration:none;
            ">
                View Full History
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
            </a>
        </div>

        {{-- Activity rows --}}
        @foreach ($configChanges as $change)
        <div style="
            display:flex; align-items:flex-start; gap:16px;
            padding:16px 0;
            border-bottom:1px solid var(--ap-border-inner);
            {{ $loop->last ? 'border-bottom:none;' : '' }}
        ">
            {{-- Icon --}}
            <div style="
                width:36px; height:36px; border-radius:10px; flex-shrink:0;
                background:{{ $change['status_bg'] }};
                display:flex; align-items:center; justify-content:center; margin-top:2px;
            ">
                @if ($change['icon_type'] === 'clock')
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $change['status_color'] }}" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                @elseif ($change['icon_type'] === 'check')
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $change['status_color'] }}" stroke-width="2.5">
                        <path d="M20.84 4.61a5.5 5.5 0 0 1 0 7.78L12 21.23l-8.84-8.84a5.5 5.5 0 0 1 7.78-7.78L12 5.67l1.06-1.06a5.5 5.5 0 0 1 7.78 0z"/>
                    </svg>
                @else
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $change['status_color'] }}" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                @endif
            </div>

            {{-- Content --}}
            <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                    <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--ap-text-subtle);">{{ $change['when'] }}</span>
                    <span style="font-size:12px; font-weight:700; color:var(--ap-text-body);">{{ $change['source'] }}</span>
                </div>
                <p style="font-size:13px; color:var(--ap-text-muted); margin:0; line-height:1.5;">
                    {!! $change['text'] !!}
                </p>
            </div>

            {{-- Status badge --}}
            <span style="
                flex-shrink:0; padding:4px 12px;
                background:{{ $change['status_bg'] }}; color:{{ $change['status_color'] }};
                font-size:9px; font-weight:800; letter-spacing:.1em;
                border-radius:20px; text-transform:uppercase; white-space:nowrap;
                margin-top:2px;
            ">{{ $change['status'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- ════ 4 · FAB ════ --}}
<div style="position:fixed; bottom:28px; right:28px; z-index:200;">
    <button type="button" title="Quick Actions" style="
        width:52px; height:52px; border-radius:50%; border:none; cursor:pointer;
        background:linear-gradient(145deg,#059669,#047857);
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 6px 24px rgba(5,150,105,0.5);
        transition:transform .2s, box-shadow .2s;
    "
    onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 8px 32px rgba(5,150,105,0.65)'"
    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 24px rgba(5,150,105,0.5)'">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff" stroke="none">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
        </svg>
    </button>
</div>

</div>{{-- .ap-root --}}
</x-filament-panels::page>
