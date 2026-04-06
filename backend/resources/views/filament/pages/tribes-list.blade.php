<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════════
     TRIBES MANAGEMENT  ·  Pixel-perfect · Dark / Light mode
     Layout: Header → Search bar → Hero + Sidebar → 3-col grid
════════════════════════════════════════════════════════════════════ --}}

{{-- ── CSS DESIGN TOKENS (auto-switch light / dark) ─────────── --}}
<style>
/* ── Light tokens ── */
:root {
    --ck-page-bg:      transparent;
    --ck-card:         #ffffff;
    --ck-card-hover:   #fafafa;
    --ck-border:       rgba(228,228,231,0.75);
    --ck-border-inner: #f1f5f9;
    --ck-text-h:       #18181b;
    --ck-text-body:    #3f3f46;
    --ck-text-muted:   #71717a;
    --ck-text-subtle:  #a1a1aa;
    --ck-surface:      rgba(248,250,252,0.85);
    --ck-input-bg:     #ffffff;
    --ck-label:        rgba(5,150,105,1);
    --ck-shadow-card:  0 2px 10px rgba(0,0,0,0.06);
    --ck-shadow-hover: 0 8px 28px rgba(0,0,0,0.10);
    --ck-hero-grad:    linear-gradient(145deg,#ffffff 0%,rgba(240,253,244,0.5) 100%);
    --ck-ai-bg:        linear-gradient(145deg,rgba(237,233,254,0.7) 0%,rgba(245,240,255,0.9) 100%);
    --ck-ai-border:    rgba(167,139,250,0.25);
    --ck-ai-title:     #7c3aed;
    --ck-ai-text:      #6d28d9;
    --ck-stat-bg:      rgba(248,250,252,0.85);
    --ck-expand-color: #059669;
    --ck-badge-active-bg:   rgba(5,150,105,0.10);
    --ck-badge-active-c:    #059669;
    --ck-badge-active-bdr:  rgba(5,150,105,0.30);
    --ck-badge-arch-bg:     rgba(161,161,170,0.10);
    --ck-badge-arch-c:      #71717a;
    --ck-badge-arch-bdr:    rgba(161,161,170,0.30);
    --ck-badge-pend-bg:     rgba(217,119,6,0.12);
    --ck-badge-pend-c:      #d97706;
    --ck-badge-pend-bdr:    rgba(217,119,6,0.30);
    --ck-preview-bg:   #fafafa;
}

/* ── Dark tokens ── */
.dark {
    --ck-page-bg:      transparent;
    --ck-card:         #1c1c27;
    --ck-card-hover:   #22222f;
    --ck-border:       rgba(63,63,70,0.8);
    --ck-border-inner: rgba(39,39,42,0.9);
    --ck-text-h:       #f4f4f5;
    --ck-text-body:    #d4d4d8;
    --ck-text-muted:   #a1a1aa;
    --ck-text-subtle:  #71717a;
    --ck-surface:      rgba(24,24,35,0.85);
    --ck-input-bg:     #27272a;
    --ck-label:        rgba(52,211,153,1);
    --ck-shadow-card:  0 2px 14px rgba(0,0,0,0.35);
    --ck-shadow-hover: 0 8px 32px rgba(0,0,0,0.50);
    --ck-hero-grad:    linear-gradient(145deg,#1c1c27 0%,rgba(24,35,30,0.8) 100%);
    --ck-ai-bg:        linear-gradient(145deg,rgba(46,36,71,0.7) 0%,rgba(38,27,64,0.9) 100%);
    --ck-ai-border:    rgba(124,58,237,0.30);
    --ck-ai-title:     #c4b5fd;
    --ck-ai-text:      #a78bfa;
    --ck-stat-bg:      rgba(30,30,42,0.85);
    --ck-expand-color: #34d399;
    --ck-badge-active-bg:   rgba(52,211,153,0.12);
    --ck-badge-active-c:    #34d399;
    --ck-badge-active-bdr:  rgba(52,211,153,0.25);
    --ck-badge-arch-bg:     rgba(113,113,122,0.15);
    --ck-badge-arch-c:      #a1a1aa;
    --ck-badge-arch-bdr:    rgba(113,113,122,0.30);
    --ck-badge-pend-bg:     rgba(251,191,36,0.10);
    --ck-badge-pend-c:      #fbbf24;
    --ck-badge-pend-bdr:    rgba(251,191,36,0.25);
    --ck-preview-bg:   rgba(30,30,42,0.7);
}

/* ── Hide default Filament page chrome ── */
.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

/* ── Global font ── */
.ck-tribes-root { font-family:'Inter','Manrope',system-ui,-apple-system,sans-serif; }

/* ── Interactions ── */
.ck-create-btn:hover{
    background: #047857 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(5,150,105,0.45) !important;
}
.ck-tribe-card{
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.ck-tribe-card:hover{
    transform: translateY(-4px);
    box-shadow: var(--ck-shadow-hover) !important;
}
.ck-tribe-card:hover .ck-card-actions{ opacity:1!important; }
.ck-hero-card{
    transition: box-shadow 0.2s ease;
}
.ck-hero-card:hover{ box-shadow: var(--ck-shadow-hover)!important; }
.ck-action-btn:hover{ transform:translateY(-1px); }
.ck-filter-input:focus{
    border-color: #059669 !important;
    box-shadow: 0 0 0 3px rgba(5,150,105,0.12) !important;
    outline: none;
}
.ck-expand-row:hover{ opacity:.8; }
</style>

<div class="ck-tribes-root">

{{-- ════════════════════════════════════════════════════════════════
     1 · PAGE HEADER
════════════════════════════════════════════════════════════════ --}}
<div style="
    display:flex; align-items:flex-start; justify-content:space-between;
    margin-bottom:28px; gap:16px;
">
    <div>
        <p style="
            font-size:10px; font-weight:800;
            text-transform:uppercase; letter-spacing:.15em;
            color:var(--ck-label); margin:0 0 6px 0;
        ">Platform Infrastructure</p>
        <h1 style="
            font-size:36px; font-weight:800;
            color:var(--ck-text-h); margin:0;
            letter-spacing:-.03em; line-height:1.1;
        ">Tribes Management</h1>
    </div>

    <a href="{{ $createUrl }}" wire:navigate class="ck-create-btn" style="
        display:inline-flex; align-items:center; gap:8px;
        padding:13px 26px;
        background:#059669; color:#fff;
        font-size:14px; font-weight:700;
        border-radius:40px; text-decoration:none;
        box-shadow:0 4px 18px rgba(5,150,105,.35);
        transition:all .2s; white-space:nowrap; flex-shrink:0; margin-top:4px;
    ">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Create Tribe
    </a>
</div>

{{-- ════════════════════════════════════════════════════════════════
     2 · SEARCH / FILTER BAR
════════════════════════════════════════════════════════════════ --}}
<div style="display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap;">

    <div style="flex:1; min-width:200px; position:relative;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--ck-text-subtle)" stroke-width="2"
             style="position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none;">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="ckSearch" type="text"
               placeholder="Search tribes or modules..."
               class="ck-filter-input"
               style="
                   width:100%; box-sizing:border-box;
                   padding:11px 14px 11px 38px;
                   border:1px solid var(--ck-border);
                   border-radius:14px;
                   font-size:13px; font-weight:500;
                   color:var(--ck-text-body);
                   background:var(--ck-input-bg);
                   transition:border-color .15s, box-shadow .15s;
               ">
    </div>

    <select wire:model.live="ckRegionFilter" class="ck-filter-input" style="
        padding:11px 14px; border:1px solid var(--ck-border);
        border-radius:14px; font-size:13px; font-weight:600;
        color:var(--ck-text-body); background:var(--ck-input-bg);
        min-width:145px; transition:border-color .15s;
    ">
        <option value="">All Regions</option>
        @foreach ($regions as $region)
            <option value="{{ $region }}">{{ $region }}</option>
        @endforeach
    </select>

    <select wire:model.live="ckStatusFilter" class="ck-filter-input" style="
        padding:11px 14px; border:1px solid var(--ck-border);
        border-radius:14px; font-size:13px; font-weight:600;
        color:var(--ck-text-body); background:var(--ck-input-bg);
        min-width:135px; transition:border-color .15s;
    ">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="archived">Archived</option>
    </select>

    @if ($ckSearch || $ckRegionFilter || $ckStatusFilter)
        <button wire:click="ckResetFilters" type="button" style="
            padding:11px 16px; font-size:13px; font-weight:700;
            color:var(--ck-expand-color); border:none; background:transparent;
            cursor:pointer; white-space:nowrap;
        ">↺ Reset</button>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════════
     3 · HERO CARD + DISTRIBUTION SIDEBAR
════════════════════════════════════════════════════════════════ --}}
@if ($featured)
@php
    $fc       = $featured;
    $emoji    = $fc->emoji_symbol ?: '🌍';
    $color    = $fc->color_hex ?: '#059669';
    $comicCnt = $fc->comics_count ?? 0;
    $isActive = $fc->is_active ?? true;
    $isExp    = in_array($fc->id, $ckExpanded);

    // Parse hex → r,g,b for rgba usage
    $r = hexdec(substr(ltrim($color,'#'), 0, 2));
    $g = hexdec(substr(ltrim($color,'#'), 2, 2));
    $b = hexdec(substr(ltrim($color,'#'), 4, 2));

    // Theme key dot (deterministic per tribe)
    $dotColors = ['#f59e0b','#ef4444','#3b82f6','#8b5cf6','#10b981','#f97316','#ec4899'];
    $dotColor  = $dotColors[$fc->id % count($dotColors)];
@endphp

<div style="display:grid; grid-template-columns:1fr 280px; gap:20px; margin-bottom:28px; align-items:start;">

    {{-- ── HERO TRIBE CARD ──────────────────────────────────── --}}
    <div class="ck-hero-card" style="
        background:var(--ck-hero-grad);
        border:1px solid var(--ck-border);
        border-radius:24px; padding:28px;
        box-shadow:var(--ck-shadow-card);
    ">
        {{-- Top row --}}
        <div style="display:flex; align-items:flex-start; gap:18px; margin-bottom:24px;">

            {{-- Emoji icon badge --}}
            <div style="
                width:72px; height:72px; flex-shrink:0;
                border-radius:20px;
                background:rgba({{ $r }},{{ $g }},{{ $b }},0.18);
                border:1.5px solid rgba({{ $r }},{{ $g }},{{ $b }},0.3);
                display:flex; align-items:center; justify-content:center;
                font-size:32px; line-height:1;
            ">{{ $emoji }}</div>

            <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:7px; flex-wrap:wrap;">
                    <h2 style="font-size:24px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.02em;">
                        {{ $fc->name }}
                    </h2>
                    {{-- Active / Archived badge --}}
                    <span style="
                        display:inline-flex; align-items:center; gap:5px;
                        padding:4px 11px; border-radius:20px; font-size:10px;
                        font-weight:800; text-transform:uppercase; letter-spacing:.08em;
                        background:{{ $isActive ? 'var(--ck-badge-active-bg)' : 'var(--ck-badge-arch-bg)' }};
                        color:{{ $isActive ? 'var(--ck-badge-active-c)' : 'var(--ck-badge-arch-c)' }};
                        border:1px solid {{ $isActive ? 'var(--ck-badge-active-bdr)' : 'var(--ck-badge-arch-bdr)' }};
                    ">
                        <span style="
                            width:6px; height:6px; border-radius:50%;
                            background:{{ $isActive ? '#10b981' : '#a1a1aa' }};
                            {{ $isActive ? 'box-shadow:0 0 5px rgba(16,185,129,.6)' : '' }};
                        "></span>
                        {{ $isActive ? 'Active' : 'Archived' }}
                    </span>
                </div>
                <p style="font-size:13px; color:var(--ck-text-muted); margin:0; line-height:1.6; max-width:480px;">
                    {{ $fc->description ?? ($fc->region ? 'Cultural heritage tribe from the ' . $fc->region . ' region.' : 'Cultural heritage and educational content tribe.') }}
                </p>
            </div>

            {{-- Theme Key --}}
            <div style="text-align:center; flex-shrink:0;">
                <div style="
                    width:28px; height:28px; border-radius:50%;
                    background:{{ $dotColor }};
                    box-shadow:0 2px 8px rgba(0,0,0,.20);
                    margin:0 auto 6px;
                "></div>
                <p style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--ck-text-subtle); margin:0; line-height:1.4;">
                    THEME<br>KEY
                </p>
            </div>
        </div>

        {{-- Stat mini-cards --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:20px;">

            <div style="background:var(--ck-stat-bg); border:1px solid var(--ck-border-inner); border-radius:16px; padding:14px 16px;">
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle); margin:0 0 5px 0;">Comics</p>
                <p style="font-size:28px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.03em;">{{ $comicCnt }}</p>
            </div>

            <div style="background:var(--ck-stat-bg); border:1px solid var(--ck-border-inner); border-radius:16px; padding:14px 16px;">
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle); margin:0 0 5px 0;">Stories</p>
                <p style="font-size:28px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.03em;">{{ $comicCnt * 4 }}</p>
            </div>

            <div style="background:var(--ck-stat-bg); border:1px solid var(--ck-border-inner); border-radius:16px; padding:14px 16px;">
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle); margin:0 0 5px 0;">Language</p>
                <p style="font-size:21px; font-weight:800; color:#7c3aed; margin:0; letter-spacing:-.01em;">{{ $fc->language ?: '—' }}</p>
            </div>
        </div>

        {{-- Visual Preview expandable row --}}
        <div style="border-top:1px solid var(--ck-border-inner);">
            <button type="button" wire:click="ckToggleExpanded({{ $fc->id }})"
                    class="ck-expand-row"
                    style="
                        display:flex; align-items:center; justify-content:space-between;
                        width:100%; padding:16px 0 {{ $isExp ? '6px' : '0px' }} 0;
                        border:none; background:transparent; cursor:pointer;
                        color:var(--ck-expand-color); font-size:13px; font-weight:700;
                        transition:opacity .15s;
                    ">
                <span>Visual Preview &amp; Brand Identity</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     style="transition:transform .2s; {{ $isExp ? 'transform:rotate(180deg)' : '' }}">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            @if ($isExp)
                <div style="
                    padding:16px; border-radius:16px;
                    background:var(--ck-preview-bg);
                    border:1px solid var(--ck-border-inner);
                    margin-bottom:4px;
                ">
                    <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                        <div style="
                            width:52px; height:52px; border-radius:16px;
                            background:{{ $color }};
                            display:flex; align-items:center; justify-content:center;
                            font-size:24px; box-shadow:0 4px 12px rgba(0,0,0,.2);
                        ">{{ $emoji }}</div>
                        <div>
                            <p style="font-size:15px; font-weight:700; color:var(--ck-text-h); margin:0;">{{ $fc->name }}</p>
                            <p style="font-size:12px; color:var(--ck-text-muted); margin:2px 0 0 0;">
                                {{ $fc->region }} · {{ $fc->language }}
                            </p>
                            @if ($fc->greeting)
                                <p style="font-size:11px; color:var(--ck-expand-color); margin:4px 0 0 0; font-style:italic;">
                                    "{{ $fc->greeting }}"@if($fc->phonetic) ({{ $fc->phonetic }})@endif
                                </p>
                            @endif
                        </div>
                        <div style="margin-left:auto; display:flex; align-items:center; gap:8px; flex-shrink:0;">
                            <div style="width:22px; height:22px; border-radius:50%; background:{{ $color }}; box-shadow:0 2px 6px rgba(0,0,0,.2);"></div>
                            <span style="font-size:11px; font-family:monospace; color:var(--ck-text-muted);">{{ $color }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Tribe switcher (click to feature another tribe) --}}
        @if ($tribes->count() > 1)
            <div style="display:flex; align-items:center; gap:8px; margin-top:16px; flex-wrap:wrap;">
                <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle);">View Tribe:</span>
                @foreach ($tribes->take(6) as $t)
                    <button type="button" wire:click="ckSetFeatured({{ $t->id }})"
                            title="{{ $t->name }}"
                            style="
                                width:30px; height:30px; border-radius:50%;
                                border:2px solid {{ $t->id === $fc->id ? 'var(--ck-expand-color)' : 'var(--ck-border)' }};
                                background:{{ $t->color_hex ?: '#059669' }}22;
                                font-size:14px; cursor:pointer; transition:all .15s;
                                display:flex; align-items:center; justify-content:center;
                            ">{{ $t->emoji_symbol ?: '🌍' }}</button>
                @endforeach
                @if ($tribes->count() > 6)
                    <span style="font-size:11px; color:var(--ck-text-subtle);">+{{ $tribes->count() - 6 }} more</span>
                @endif
            </div>
        @endif
    </div>

    {{-- ── RIGHT SIDEBAR ────────────────────────────────────── --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- Distribution card --}}
        <div style="
            background:var(--ck-card);
            border:1px solid var(--ck-border);
            border-radius:20px; padding:24px;
            box-shadow:var(--ck-shadow-card);
        ">
            <h3 style="font-size:18px; font-weight:800; color:var(--ck-text-h); margin:0 0 20px 0; letter-spacing:-.02em;">
                Distribution
            </h3>

            {{-- Active --}}
            <div style="margin-bottom:18px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="font-size:13px; font-weight:600; color:var(--ck-text-body);">Active Tribes</span>
                    <span style="font-size:18px; font-weight:800; color:var(--ck-text-h);">{{ $activeCnt }}</span>
                </div>
                <div style="height:6px; background:var(--ck-border-inner); border-radius:99px; overflow:hidden;">
                    <div style="
                        height:100%;
                        width:{{ $total > 0 ? round(($activeCnt / $total) * 100) : 0 }}%;
                        background:linear-gradient(90deg,#059669,#10b981);
                        border-radius:99px; transition:width .4s ease;
                    "></div>
                </div>
            </div>

            {{-- Archived --}}
            <div>
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="font-size:13px; font-weight:600; color:var(--ck-text-body);">Archived</span>
                    <span style="font-size:18px; font-weight:800; color:var(--ck-text-h);">{{ $archivedCnt }}</span>
                </div>
                <div style="height:6px; background:var(--ck-border-inner); border-radius:99px; overflow:hidden;">
                    <div style="
                        height:100%;
                        width:{{ $total > 0 ? round(($archivedCnt / $total) * 100) : 0 }}%;
                        background:linear-gradient(90deg,#92400e,#b45309);
                        border-radius:99px; transition:width .4s ease;
                    "></div>
                </div>
            </div>
        </div>

        {{-- AI Suggestion card --}}
        <div style="
            background:var(--ck-ai-bg);
            border:1px solid var(--ck-ai-border);
            border-radius:20px; padding:20px;
            box-shadow:0 2px 10px rgba(109,40,217,.08);
        ">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                <span style="font-size:20px; line-height:1;">✨</span>
                <span style="font-size:14px; font-weight:800; color:var(--ck-ai-title);">New Suggestion</span>
            </div>
            <p style="font-size:12px; color:var(--ck-ai-text); line-height:1.65; margin:0;">
                Consider creating a 'Zulu' tribe module based on recent user demand in the Southern Africa region.
            </p>
        </div>

        {{-- Quick stats card --}}
        <div style="
            background:var(--ck-card);
            border:1px solid var(--ck-border);
            border-radius:20px; padding:16px 20px;
            box-shadow:var(--ck-shadow-card);
        ">
            <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--ck-text-subtle); margin:0 0 12px 0;">Quick Stats</p>
            <div style="display:flex; justify-content:space-between;">
                <div style="text-align:center;">
                    <p style="font-size:24px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.03em;">{{ $tribes->count() }}</p>
                    <p style="font-size:10px; color:var(--ck-text-subtle); margin:2px 0 0 0; font-weight:600;">Total</p>
                </div>
                <div style="text-align:center;">
                    <p style="font-size:24px; font-weight:800; color:#059669; margin:0; letter-spacing:-.03em;">{{ $activeCnt }}</p>
                    <p style="font-size:10px; color:var(--ck-text-subtle); margin:2px 0 0 0; font-weight:600;">Active</p>
                </div>
                <div style="text-align:center;">
                    <p style="font-size:24px; font-weight:800; color:#b45309; margin:0; letter-spacing:-.03em;">{{ $archivedCnt }}</p>
                    <p style="font-size:10px; color:var(--ck-text-subtle); margin:2px 0 0 0; font-weight:600;">Archived</p>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end 2-col --}}
@endif

{{-- ════════════════════════════════════════════════════════════════
     4 · TRIBE GRID CARDS
════════════════════════════════════════════════════════════════ --}}
@if ($gridTribes->isNotEmpty())
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:18px;">

        @foreach ($gridTribes as $tribe)
        @php
            $tc       = $tribe;
            $tEmoji   = $tc->emoji_symbol ?: '🌍';
            $tColor   = $tc->color_hex ?: '#059669';
            $tComics  = $tc->comics_count ?? 0;
            $tActive  = $tc->is_active ?? true;
            $tExp     = in_array($tc->id, $ckExpanded);

            $tr = hexdec(substr(ltrim($tColor,'#'),0,2));
            $tg = hexdec(substr(ltrim($tColor,'#'),2,2));
            $tb = hexdec(substr(ltrim($tColor,'#'),4,2));

            // Status label/color
            if ($tActive) {
                $tSLabel = 'ACTIVE'; $tSDot = '#10b981'; $tSVar = 'var(--ck-badge-active-bg),var(--ck-badge-active-c),var(--ck-badge-active-bdr)';
            } else {
                $tSLabel = 'PENDING REVIEW'; $tSDot = '#f59e0b'; $tSVar = 'var(--ck-badge-pend-bg),var(--ck-badge-pend-c),var(--ck-badge-pend-bdr)';
            }

            // Progress bar colour (deterministic per tribe)
            $barColors = ['#ef4444','#8b5cf6','#10b981','#f59e0b','#3b82f6','#ec4899','#059669','#f97316'];
            $barColor = $barColors[$tc->id % count($barColors)];

            // Bar width relative to max in grid
            $maxC = max((int)$gridTribes->max('comics_count'), 1);
            $barW = $maxC > 0 ? min(round(($tComics / $maxC) * 100), 100) : 8;

            $desc = $tc->description ?? ($tc->region ? 'Cultural heritage tribe from the ' . $tc->region . ' region.' : 'Cultural heritage and educational content tribe.');
            if (strlen($desc) > 85) $desc = substr($desc, 0, 83) . '…';
        @endphp

        <div class="ck-tribe-card" style="
            background:var(--ck-card);
            border:1px solid var(--ck-border);
            border-radius:22px; padding:20px;
            box-shadow:var(--ck-shadow-card);
            position:relative;
        ">
            {{-- Top row: icon + status --}}
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
                <div style="
                    width:56px; height:56px; border-radius:18px;
                    background:rgba({{ $tr }},{{ $tg }},{{ $tb }},.15);
                    border:1px solid rgba({{ $tr }},{{ $tg }},{{ $tb }},.25);
                    display:flex; align-items:center; justify-content:center; font-size:26px;
                ">{{ $tEmoji }}</div>

                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:5px;">
                    <div style="
                        width:12px; height:12px; border-radius:50%;
                        background:{{ $tSDot }};
                        box-shadow:0 0 6px {{ $tSDot }}80;
                    "></div>
                    <span style="
                        display:inline-block; padding:3px 9px;
                        border-radius:20px; font-size:8px; font-weight:800;
                        text-transform:uppercase; letter-spacing:.06em;
                        background:{{ $tActive ? 'var(--ck-badge-active-bg)' : 'var(--ck-badge-pend-bg)' }};
                        color:{{ $tActive ? 'var(--ck-badge-active-c)' : 'var(--ck-badge-pend-c)' }};
                        border:1px solid {{ $tActive ? 'var(--ck-badge-active-bdr)' : 'var(--ck-badge-pend-bdr)' }};
                    ">{{ $tSLabel }}</span>
                </div>
            </div>

            {{-- Name + description --}}
            <h3 style="font-size:17px; font-weight:800; color:var(--ck-text-h); margin:0 0 6px 0; letter-spacing:-.02em;">
                {{ $tc->name }}
            </h3>
            <p style="font-size:12px; color:var(--ck-text-muted); margin:0 0 18px 0; line-height:1.6; min-height:36px;">
                {{ $desc }}
            </p>

            {{-- Comics count + progress bar --}}
            <div style="margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:7px;">
                    <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--ck-text-subtle);">Comics Count</span>
                    <span style="font-size:14px; font-weight:800; color:var(--ck-text-h);">{{ $tComics }}</span>
                </div>
                <div style="height:4px; background:var(--ck-border-inner); border-radius:99px; overflow:hidden;">
                    <div style="
                        height:100%; width:{{ max($barW, 6) }}%;
                        background:{{ $barColor }};
                        border-radius:99px; transition:width .4s ease;
                    "></div>
                </div>
            </div>

            {{-- Preview Branding expandable --}}
            <div style="border-top:1px solid var(--ck-border-inner);">
                <button type="button" wire:click="ckToggleExpanded({{ $tc->id }})"
                        class="ck-expand-row"
                        style="
                            display:flex; align-items:center; justify-content:space-between;
                            width:100%; padding:12px 0 0 0;
                            border:none; background:transparent; cursor:pointer;
                            color:var(--ck-expand-color); font-size:12px; font-weight:700;
                        ">
                    <span>Preview Branding</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         style="transition:transform .2s; {{ $tExp ? 'transform:rotate(180deg)' : '' }}">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                @if ($tExp)
                    <div style="
                        padding:12px; border-radius:14px;
                        background:var(--ck-preview-bg);
                        border:1px solid var(--ck-border-inner);
                        margin-top:10px;
                    ">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="
                                width:40px; height:40px; border-radius:12px;
                                background:{{ $tColor }};
                                display:flex; align-items:center; justify-content:center; font-size:20px;
                            ">{{ $tEmoji }}</div>
                            <div style="flex:1; min-width:0;">
                                <p style="font-size:13px; font-weight:700; color:var(--ck-text-h); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $tc->name }}</p>
                                <p style="font-size:11px; color:var(--ck-text-muted); margin:1px 0 0 0;">{{ $tc->language }} · {{ $tc->region }}</p>
                            </div>
                            <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                <div style="width:18px; height:18px; border-radius:50%; background:{{ $tColor }}; box-shadow:0 2px 6px rgba(0,0,0,.2);"></div>
                                <span style="font-size:10px; font-family:monospace; color:var(--ck-text-muted);">{{ $tColor }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Hover actions --}}
            <div class="ck-card-actions" style="
                display:flex; gap:6px; margin-top:14px;
                opacity:0; transition:opacity .2s;
            ">
                <a href="{{ \App\Filament\Resources\TribeResource::getUrl('edit', ['record' => $tc]) }}" wire:navigate
                   class="ck-action-btn"
                   style="
                       flex:1; display:flex; align-items:center; justify-content:center; gap:5px;
                       padding:8px; border:1px solid var(--ck-border);
                       border-radius:10px; background:var(--ck-card-hover);
                       color:var(--ck-text-body); font-size:11px; font-weight:600;
                       text-decoration:none; transition:all .15s;
                   ">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </a>
                <button type="button"
                        wire:click="ckToggleActive({{ $tc->id }})"
                        wire:confirm="{{ $tActive ? 'Archive' : 'Activate' }} {{ $tc->name }}?"
                        class="ck-action-btn"
                        style="
                            flex:1; display:flex; align-items:center; justify-content:center; gap:5px;
                            padding:8px;
                            border:1px solid {{ $tActive ? '#fecaca' : '#bbf7d0' }};
                            border-radius:10px;
                            background:{{ $tActive ? '#fef2f2' : '#f0fdf4' }};
                            color:{{ $tActive ? '#dc2626' : '#059669' }};
                            font-size:11px; font-weight:600; cursor:pointer; transition:all .15s;
                        ">
                    @if ($tActive)
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>
                        </svg>
                        Archive
                    @else
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                        Activate
                    @endif
                </button>
            </div>
        </div>
        @endforeach

    </div>{{-- end grid --}}

@elseif (!$featured)
    {{-- Empty state --}}
    <div style="text-align:center; padding:80px 20px;">
        <p style="font-size:56px; margin:0 0 16px;">🌍</p>
        <h3 style="font-size:18px; font-weight:700; color:var(--ck-text-muted); margin:0 0 8px;">No tribes found</h3>
        <p style="font-size:13px; color:var(--ck-text-subtle); margin:0 0 20px;">Adjust your filters or create the first tribe.</p>
        <a href="{{ $createUrl }}" wire:navigate style="
            display:inline-flex; align-items:center; gap:8px;
            padding:12px 24px; background:#059669; color:#fff;
            font-size:13px; font-weight:700; border-radius:30px;
            text-decoration:none; box-shadow:0 4px 14px rgba(5,150,105,.3);
        ">+ Create First Tribe</a>
    </div>
@endif

<div style="height:48px;"></div>
</div>{{-- .ck-tribes-root --}}
</x-filament-panels::page>
