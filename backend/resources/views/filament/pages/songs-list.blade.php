<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════════
     SONGS & AUDIO CMS  ·  Pixel-perfect  ·  Dark / Light mode
     Design: Header → 4 KPI cards → Tab Nav → 4-col grid → Load more
════════════════════════════════════════════════════════════════════ --}}

<style>
/* ── Light tokens ── */
:root {
    --sa-card:         #ffffff;
    --sa-card-hover:   #f8fafc;
    --sa-border:       rgba(228,228,231,0.75);
    --sa-border-inner: #f1f5f9;
    --sa-text-h:       #18181b;
    --sa-text-body:    #3f3f46;
    --sa-text-muted:   #71717a;
    --sa-text-subtle:  #a1a1aa;
    --sa-input-bg:     #ffffff;
    --sa-surface:      rgba(248,250,252,0.9);
    --sa-shadow-sm:    0 1px 4px rgba(0,0,0,0.06);
    --sa-shadow-md:    0 4px 16px rgba(0,0,0,0.08);
    --sa-shadow-lg:    0 10px 32px rgba(0,0,0,0.12);
    --sa-tab-active:   #059669;
    --sa-btn-outline:  #e4e4e7;
    --sa-kpi-bg:       #ffffff;
    --sa-overlay-bg:   rgba(0,0,0,0.05);
}
/* ── Dark tokens ── */
.dark {
    --sa-card:         #1c1c27;
    --sa-card-hover:   #22222f;
    --sa-border:       rgba(63,63,70,0.8);
    --sa-border-inner: rgba(39,39,42,0.9);
    --sa-text-h:       #f4f4f5;
    --sa-text-body:    #d4d4d8;
    --sa-text-muted:   #a1a1aa;
    --sa-text-subtle:  #52525b;
    --sa-input-bg:     #27272a;
    --sa-surface:      rgba(24,24,35,0.85);
    --sa-shadow-sm:    0 1px 6px rgba(0,0,0,0.4);
    --sa-shadow-md:    0 4px 20px rgba(0,0,0,0.45);
    --sa-shadow-lg:    0 10px 36px rgba(0,0,0,0.6);
    --sa-tab-active:   #34d399;
    --sa-btn-outline:  rgba(63,63,70,0.9);
    --sa-kpi-bg:       #1c1c27;
    --sa-overlay-bg:   rgba(0,0,0,0.3);
}

/* ── Filament chrome ── */
.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

/* ── Root ── */
.sa-root { font-family:'Inter','Manrope',system-ui,-apple-system,sans-serif; max-width:1320px; margin:0 auto; }

/* ── Card hover ── */
.sa-track-card { transition: transform 0.22s ease, box-shadow 0.22s ease; }
.sa-track-card:hover { transform:translateY(-4px); box-shadow:var(--sa-shadow-lg)!important; }
.sa-track-card:hover .sa-card-overlay { opacity:1!important; }

/* ── KPI cards ── */
.sa-kpi-card { transition: transform .18s, box-shadow .18s; }
.sa-kpi-card:hover { transform:translateY(-2px); box-shadow:var(--sa-shadow-md)!important; }

/* ── Buttons ── */
.sa-upload-btn:hover { background:#047857!important; transform:translateY(-2px); box-shadow:0 8px 28px rgba(5,150,105,0.45)!important; }
.sa-load-btn:hover { background:var(--sa-surface)!important; }
.sa-filter-input:focus { border-color:#059669!important; box-shadow:0 0 0 3px rgba(5,150,105,.12)!important; outline:none; }

/* ── Three-dot menu ── */
.sa-menu-btn { transition:background .15s; }
.sa-menu-btn:hover { background:var(--sa-overlay-bg)!important; }

/* ── Tab transitions ── */
.sa-tab { transition: all .15s; }
</style>

<div class="sa-root">

{{-- ════════════════════════════════════════════════════════════════
     1 · PAGE HEADER
════════════════════════════════════════════════════════════════ --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; gap:16px;">
    <div>
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
            <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--sa-text-subtle);">Content</span>
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--sa-text-subtle)" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:#059669;">Audio Management</span>
        </div>
        <h1 style="font-size:34px; font-weight:800; color:var(--sa-text-h); margin:0; letter-spacing:-.03em; line-height:1.1;">
            Songs &amp; Audio
        </h1>
        <p style="font-size:13px; color:var(--sa-text-muted); margin:7px 0 0; font-weight:400; line-height:1.5;">
            Curate and manage your cultural sonic heritage library.
        </p>
    </div>

    <a href="{{ $createUrl }}" wire:navigate class="sa-upload-btn" style="
        display:inline-flex; align-items:center; gap:9px;
        padding:13px 26px;
        background:#059669; color:#fff;
        font-size:14px; font-weight:700;
        border-radius:40px; text-decoration:none;
        box-shadow:0 4px 18px rgba(5,150,105,.35);
        transition:all .2s; white-space:nowrap; flex-shrink:0; margin-top:6px;
    ">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="17 8 12 3 7 8"/>
            <line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
        Upload New Audio
    </a>
</div>

{{-- ════════════════════════════════════════════════════════════════
     2 · KPI STAT CARDS (4 columns, matching design)
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">

    {{-- Card 1: Total Tracks --}}
    <div class="sa-kpi-card" style="
        background:var(--sa-kpi-bg); border:1px solid var(--sa-border);
        border-radius:20px; padding:20px 22px;
        box-shadow:var(--sa-shadow-sm); position:relative;
    ">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
            <div style="
                width:42px; height:42px; border-radius:12px;
                background:rgba(5,150,105,0.12);
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </div>
            <span style="
                font-size:9px; font-weight:800; color:#059669;
                background:rgba(5,150,105,0.1); padding:3px 8px;
                border-radius:20px; letter-spacing:.06em;
            ">+12%</span>
        </div>
        <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--sa-text-subtle); margin:0 0 5px;">Total Tracks</p>
        <p style="font-size:28px; font-weight:800; color:var(--sa-text-h); margin:0; letter-spacing:-.03em;">{{ number_format($totalTracks) }}</p>
    </div>

    {{-- Card 2: Streaming Orgs --}}
    <div class="sa-kpi-card" style="
        background:var(--sa-kpi-bg); border:1px solid var(--sa-border);
        border-radius:20px; padding:20px 22px;
        box-shadow:var(--sa-shadow-sm); position:relative;
    ">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
            <div style="
                width:42px; height:42px; border-radius:12px;
                background:rgba(234,88,12,0.12);
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M6.343 6.343a8 8 0 0 0 0 11.314M17.657 6.343a8 8 0 0 1 0 11.314"/>
                    <path d="M3.515 3.515a12 12 0 0 0 0 16.97M20.485 3.515a12 12 0 0 1 0 16.97"/>
                </svg>
            </div>
            <span style="
                font-size:9px; font-weight:800; color:#ea580c;
                background:rgba(234,88,12,0.1); padding:3px 8px;
                border-radius:20px; letter-spacing:.06em;
            ">{{ $liveCount }} Active</span>
        </div>
        <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--sa-text-subtle); margin:0 0 5px;">Streaming Org</p>
        <p style="font-size:28px; font-weight:800; color:var(--sa-text-h); margin:0; letter-spacing:-.03em;">{{ $streamingOrgs ?: '—' }}</p>
    </div>

    {{-- Card 3: Avg Playtime --}}
    <div class="sa-kpi-card" style="
        background:var(--sa-kpi-bg); border:1px solid var(--sa-border);
        border-radius:20px; padding:20px 22px;
        box-shadow:var(--sa-shadow-sm); position:relative;
    ">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
            <div style="
                width:42px; height:42px; border-radius:12px;
                background:rgba(124,58,237,0.12);
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2">
                    <path d="M9 18V5l12-2v13"/>
                    <circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
                </svg>
            </div>
            <span style="
                font-size:9px; font-weight:800; color:#d97706;
                background:rgba(217,119,6,0.1); padding:3px 8px;
                border-radius:20px; letter-spacing:.06em;
            ">High</span>
        </div>
        <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--sa-text-subtle); margin:0 0 5px;">Avg Playtime</p>
        <p style="font-size:24px; font-weight:800; color:var(--sa-text-h); margin:0; letter-spacing:-.03em;">{{ $avgPlaytime ?: '—' }}</p>
    </div>

    {{-- Card 4: Storage Used --}}
    <div class="sa-kpi-card" style="
        background:var(--sa-kpi-bg); border:1px solid var(--sa-border);
        border-radius:20px; padding:20px 22px;
        box-shadow:var(--sa-shadow-sm); position:relative;
    ">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
            <div style="
                width:42px; height:42px; border-radius:12px;
                background:rgba(24,24,27,0.1);
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--sa-text-body)" stroke-width="2">
                    <ellipse cx="12" cy="5" rx="9" ry="3"/>
                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                </svg>
            </div>
            <span style="
                font-size:9px; font-weight:800; color:var(--sa-text-muted);
                background:var(--sa-border-inner); padding:3px 8px;
                border-radius:20px; letter-spacing:.06em; border:1px solid var(--sa-border);
            ">{{ $storageUsedPct }}% Full</span>
        </div>
        <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--sa-text-subtle); margin:0 0 5px;">Storage Used</p>
        <p style="font-size:28px; font-weight:800; color:var(--sa-text-h); margin:0; letter-spacing:-.03em;">{{ $storageGB ?: '0 MB' }}</p>
        {{-- Storage bar --}}
        <div style="margin-top:10px; height:3px; background:var(--sa-border-inner); border-radius:99px; overflow:hidden;">
            <div style="height:100%; width:{{ $storageUsedPct }}%; background:#059669; border-radius:99px; transition:width .4s;"></div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     3 · SEARCH (top bar area) + TAB NAV + VIEW TOGGLE
════════════════════════════════════════════════════════════════ --}}
{{-- Search --}}
<div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
    <div style="flex:1; min-width:220px; position:relative;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--sa-text-subtle)" stroke-width="2"
             style="position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none;">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="ckSearch" type="text"
               placeholder="Search audio library, tribes, or episodes..."
               class="sa-filter-input"
               style="
                   width:100%; box-sizing:border-box;
                   padding:11px 14px 11px 36px;
                   border:1px solid var(--sa-border); border-radius:40px;
                   font-size:13px; font-weight:500;
                   color:var(--sa-text-body); background:var(--sa-input-bg);
                   transition:border-color .15s, box-shadow .15s;
               ">
    </div>

    <select wire:model.live="ckTribeFilter" class="sa-filter-input" style="
        padding:11px 14px; border:1px solid var(--sa-border);
        border-radius:14px; font-size:13px; font-weight:600;
        color:var(--sa-text-body); background:var(--sa-input-bg); min-width:140px;
    ">
        <option value="">All Tribes</option>
        @foreach ($tribes as $tribe)
            <option value="{{ $tribe->id }}">{{ $tribe->name }}</option>
        @endforeach
    </select>

    <button type="button" style="
        display:inline-flex; align-items:center; gap:8px;
        padding:11px 20px; border:1px solid var(--sa-border);
        border-radius:14px; background:var(--sa-card);
        font-size:13px; font-weight:600; color:var(--sa-text-body); cursor:pointer;
    ">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
        Filter
    </button>
</div>

{{-- Tab nav + view toggle row --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; border-bottom:1px solid var(--sa-border);">
    {{-- Tabs --}}
    <div style="display:flex; align-items:center; gap:0;">
        @php
            $tabs = [
                'all'      => 'All Library',
                'popular'  => 'Popular',
                'recent'   => 'Recently Added',
                'byTribe'  => 'By Tribe',
            ];
        @endphp
        @foreach ($tabs as $key => $label)
            <button type="button" wire:click="ckSetTab('{{ $key }}')"
                    class="sa-tab"
                    style="
                        padding:12px 18px; border:none; background:transparent; cursor:pointer;
                        font-size:13px; font-weight:{{ $ckTab === $key ? '700' : '600' }};
                        color:{{ $ckTab === $key ? 'var(--sa-tab-active)' : 'var(--sa-text-muted)' }};
                        border-bottom:{{ $ckTab === $key ? '2.5px solid var(--sa-tab-active)' : '2.5px solid transparent' }};
                        margin-bottom:-1px;
                        white-space:nowrap;
                    ">{{ $label }}</button>
        @endforeach
    </div>

    {{-- View mode toggle --}}
    <div style="display:flex; align-items:center; gap:10px; padding-bottom:12px;">
        <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--sa-text-subtle);">View Mode:</span>
        <button wire:click="ckSetView('grid')" type="button" style="
            width:32px; height:32px; border-radius:8px; border:1px solid var(--sa-border);
            background:{{ $ckView === 'grid' ? '#059669' : 'var(--sa-card)' }};
            color:{{ $ckView === 'grid' ? '#fff' : 'var(--sa-text-muted)' }};
            cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
            </svg>
        </button>
        <button wire:click="ckSetView('list')" type="button" style="
            width:32px; height:32px; border-radius:8px; border:1px solid var(--sa-border);
            background:{{ $ckView === 'list' ? '#059669' : 'var(--sa-card)' }};
            color:{{ $ckView === 'list' ? '#fff' : 'var(--sa-text-muted)' }};
            cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     4 · AUDIO TRACK GRID / LIST
════════════════════════════════════════════════════════════════ --}}

@if ($tracks->isEmpty())
    <div style="text-align:center; padding:80px 20px;">
        <p style="font-size:52px; margin:0 0 16px;">🎵</p>
        <h3 style="font-size:18px; font-weight:700; color:var(--sa-text-muted); margin:0 0 8px;">No tracks found</h3>
        <p style="font-size:13px; color:var(--sa-text-subtle); margin:0 0 20px;">Upload your first cultural audio track to get started.</p>
        <a href="{{ $createUrl }}" wire:navigate style="
            display:inline-flex; align-items:center; gap:8px;
            padding:12px 24px; background:#059669; color:#fff;
            font-size:13px; font-weight:700; border-radius:30px;
            text-decoration:none; box-shadow:0 4px 14px rgba(5,150,105,.3);
        ">+ Upload First Track</a>
    </div>

@elseif ($ckView === 'grid')
    {{-- ── GRID VIEW (4 columns matching design) ── --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">
        @foreach ($tracks as $track)
        @php
            $cover     = $track->cover_image_path
                ? \Illuminate\Support\Facades\Storage::url($track->cover_image_path)
                : null;
            $duration  = sprintf('%02d:%02d', intdiv($track->duration_seconds ?? 0, 60), ($track->duration_seconds ?? 0) % 60);
            $tribeName = $track->tribe?->name;

            $status = $track->status;
            $statusBg  = match($status) {
                'live'       => '#059669',
                'processing' => '#d97706',
                'archived'   => '#6b7280',
                default      => '#6b7280',
            };
            $statusLabel = match($status) {
                'live'       => 'LIVE',
                'processing' => 'PROCESSING',
                'archived'   => 'ARCHIVED',
                default      => strtoupper($status),
            };

            // Category → tribe dot color & label
            $catColors = [
                'yoruba_tribe'      => ['#059669', 'YORUBA TRIBE'],
                'igbo_tribe'        => ['#059669', 'IGBO TRIBE'],
                'zulu_oral_history' => ['#d97706', 'ZULU ORAL HISTORY'],
                'nature_ambience'   => ['#059669', 'NATURE AMBIENCE'],
                'lullabies'         => ['#059669', 'LULLABIES'],
                'drumming'          => ['#7c3aed', 'DRUMMING'],
                'general'           => ['#71717a', 'GENERAL'],
            ];
            [$catColor, $catLabel] = $catColors[$track->category ?? 'general'] ?? ['#71717a', strtoupper($track->category ?? 'GENERAL')];

            // Override with tribe name if available
            if ($tribeName) {
                $catLabel = strtoupper($tribeName);
            }

            // Cover gradient fallback
            $coverGrads = [
                'linear-gradient(160deg,#0d0d0d 0%,#1a1a1a 40%,#2d1a0e 100%)',
                'linear-gradient(160deg,#0a0a1a 0%,#1a0a0a 40%,#2a0a00 100%)',
                'linear-gradient(160deg,#001a00 0%,#0a2a0a 40%,#004d00 100%)',
                'linear-gradient(160deg,#1a1a2e 0%,#16213e 40%,#0f3460 100%)',
                'linear-gradient(160deg,#2d0a2d 0%,#1a001a 40%,#3d0a3d 100%)',
                'linear-gradient(160deg,#2a1a00 0%,#3d2600 40%,#1a0d00 100%)',
            ];
            $coverGrad = $coverGrads[$track->id % count($coverGrads)];
        @endphp

        <div class="sa-track-card" style="
            background:var(--sa-card); border:1px solid var(--sa-border);
            border-radius:20px; overflow:hidden;
            box-shadow:var(--sa-shadow-sm); position:relative;
        ">
            {{-- Cover image area --}}
            <div style="position:relative; overflow:hidden; height:200px; background:{{ $coverGrad }};">
                @if ($cover)
                    <img src="{{ $cover }}" alt="{{ $track->title }}" style="width:100%; height:100%; object-fit:cover; transition:transform .3s;">
                @else
                    {{-- Artistic placeholder from category --}}
                    <div style="
                        width:100%; height:100%;
                        display:flex; align-items:center; justify-content:center;
                        flex-direction:column; gap:8px;
                    ">
                        @if (str_contains($track->category ?? '', 'nature'))
                            <span style="font-size:48px; filter:drop-shadow(0 0 20px rgba(0,180,0,0.6));">🌿</span>
                        @elseif (str_contains($track->category ?? '', 'lullab'))
                            <span style="font-size:48px; filter:drop-shadow(0 0 20px rgba(255,200,0,0.5));">🌙</span>
                        @elseif (str_contains($track->category ?? '', 'drum'))
                            <span style="font-size:48px; filter:drop-shadow(0 0 20px rgba(200,100,0,0.5));">🥁</span>
                        @elseif (str_contains($track->category ?? '', 'oral') || str_contains($track->category ?? '', 'zulu'))
                            <span style="font-size:48px; filter:drop-shadow(0 0 20px rgba(255,100,0,0.5));">📜</span>
                        @else
                            <span style="font-size:48px; filter:drop-shadow(0 0 20px rgba(100,200,100,0.4));">🎵</span>
                        @endif
                        <p style="font-size:11px; font-weight:600; color:rgba(255,255,255,0.5); text-align:center; padding:0 12px; margin:0;">{{ $track->title }}</p>
                    </div>
                @endif

                {{-- Status badge - top left --}}
                <span style="
                    position:absolute; top:10px; left:10px;
                    padding:4px 10px; border-radius:20px;
                    font-size:9px; font-weight:800; letter-spacing:.08em;
                    background:{{ $statusBg }}; color:#fff;
                    box-shadow:0 2px 6px rgba(0,0,0,0.35);
                ">{{ $statusLabel }}</span>

                {{-- Play / processing button - bottom right --}}
                <div style="
                    position:absolute; bottom:10px; right:10px;
                    width:36px; height:36px; border-radius:50%;
                    background:rgba(255,255,255,0.15); backdrop-filter:blur(8px);
                    display:flex; align-items:center; justify-content:center;
                    border:1px solid rgba(255,255,255,0.25);
                    cursor:pointer;
                ">
                    @if ($status === 'processing')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
                            <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                        </svg>
                    @else
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#fff" stroke="#fff" stroke-width="1">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                    @endif
                </div>

                {{-- Hover overlay --}}
                <div class="sa-card-overlay" style="
                    position:absolute; inset:0; background:rgba(0,0,0,0.55);
                    display:flex; align-items:center; justify-content:center; gap:8px;
                    opacity:0; transition:opacity .2s;
                ">
                    <a href="{{ \App\Filament\Resources\AudioTrackResource::getUrl('edit', ['record' => $track]) }}" wire:navigate
                       style="padding:8px 16px; background:#fff; color:#18181b; font-size:12px; font-weight:700; border-radius:20px; text-decoration:none;">
                        ✏️ Edit
                    </a>
                    @if ($track->status !== 'live')
                        <button wire:click="ckMakeLive({{ $track->id }})" wire:confirm="Make '{{ $track->title }}' live?" type="button"
                                style="padding:8px 16px; background:#059669; color:#fff; font-size:12px; font-weight:700; border-radius:20px; border:none; cursor:pointer;">
                            ▶ Go Live
                        </button>
                    @endif
                </div>
            </div>

            {{-- Card body --}}
            <div style="padding:14px 16px;">
                {{-- Category / tribe tag --}}
                <div style="display:flex; align-items:center; gap:5px; margin-bottom:7px;">
                    <div style="width:7px; height:7px; border-radius:50%; background:{{ $catColor }};"></div>
                    <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:{{ $catColor }};">{{ $catLabel }}</span>
                </div>

                {{-- Title --}}
                <h3 style="font-size:15px; font-weight:800; color:var(--sa-text-h); margin:0 0 3px; letter-spacing:-.02em; line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $track->title }}
                </h3>

                {{-- Subtitle --}}
                @if ($track->subtitle)
                    <p style="font-size:11px; color:var(--sa-text-muted); margin:0 0 12px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $track->subtitle }}
                    </p>
                @else
                    <div style="margin-bottom:12px;"></div>
                @endif

                {{-- Duration + menu --}}
                <div style="display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--sa-border-inner); padding-top:10px;">
                    <div style="display:flex; align-items:center; gap:5px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--sa-text-subtle)" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span style="font-size:12px; font-weight:700; color:var(--sa-text-body);">{{ $duration }}</span>
                        @if ($track->play_count > 0)
                            <span style="font-size:10px; color:var(--sa-text-subtle); margin-left:6px;">· {{ number_format($track->play_count) }} plays</span>
                        @endif
                    </div>
                    <button type="button" class="sa-menu-btn" style="
                        width:28px; height:28px; border-radius:50%; border:none; background:transparent;
                        cursor:pointer; display:flex; align-items:center; justify-content:center;
                        color:var(--sa-text-subtle);
                    ">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@else
    {{-- ── LIST VIEW ── --}}
    <div style="background:var(--sa-card); border:1px solid var(--sa-border); border-radius:20px; overflow:hidden; box-shadow:var(--sa-shadow-sm); margin-bottom:28px;">
        <div style="display:grid; grid-template-columns:52px 1fr 150px 120px 80px 90px 100px; gap:0; padding:12px 20px; background:var(--sa-surface); border-bottom:1px solid var(--sa-border); font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--sa-text-subtle); align-items:center;">
            <span></span>
            <span>Track</span>
            <span>Category</span>
            <span>Tribe</span>
            <span>Duration</span>
            <span>Status</span>
            <span style="text-align:right;">Actions</span>
        </div>
        @foreach ($tracks as $track)
        @php
            $cover    = $track->cover_image_path ? \Illuminate\Support\Facades\Storage::url($track->cover_image_path) : null;
            $duration = sprintf('%02d:%02d', intdiv($track->duration_seconds ?? 0, 60), ($track->duration_seconds ?? 0) % 60);
            $sc = match($track->status) {
                'live'       => ['Live',       '#059669', 'rgba(5,150,105,0.1)'],
                'processing' => ['Processing', '#d97706', 'rgba(217,119,6,0.1)'],
                'archived'   => ['Archived',   '#6b7280', 'rgba(107,114,128,0.1)'],
                default      => [ucfirst($track->status), '#6b7280', 'rgba(107,114,128,0.1)'],
            };
        @endphp
        <div style="display:grid; grid-template-columns:52px 1fr 150px 120px 80px 90px 100px; gap:0; padding:13px 20px; align-items:center; border-bottom:1px solid var(--sa-border-inner); transition:background .15s;" class="sa-list-row">
            <div>
                <div style="width:42px; height:42px; border-radius:10px; overflow:hidden; background:var(--sa-surface);">
                    @if ($cover)
                        <img src="{{ $cover }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:18px;">🎵</div>
                    @endif
                </div>
            </div>
            <div style="min-width:0; padding-right:12px;">
                <p style="font-size:14px; font-weight:700; color:var(--sa-text-h); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $track->title }}</p>
                @if ($track->subtitle)
                    <p style="font-size:11px; color:var(--sa-text-muted); margin:1px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $track->subtitle }}</p>
                @endif
            </div>
            <div><span style="font-size:11px; font-weight:600; color:var(--sa-text-body);">{{ ucwords(str_replace('_',' ',$track->category ?? 'general')) }}</span></div>
            <div><span style="font-size:11px; color:var(--sa-text-muted);">{{ $track->tribe?->name ?? '—' }}</span></div>
            <div><span style="font-size:12px; font-weight:700; color:var(--sa-text-body);">{{ $duration }}</span></div>
            <div><span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; background:{{ $sc[2] }}; color:{{ $sc[1] }};">{{ $sc[0] }}</span></div>
            <div style="display:flex; gap:6px; justify-content:flex-end;">
                <a href="{{ \App\Filament\Resources\AudioTrackResource::getUrl('edit', ['record' => $track]) }}" wire:navigate
                   style="font-size:11px; font-weight:600; color:var(--sa-text-body); padding:5px 12px; border:1px solid var(--sa-border); border-radius:8px; background:var(--sa-card); text-decoration:none;">Edit</a>
                @if ($track->status !== 'live')
                    <button wire:click="ckMakeLive({{ $track->id }})" wire:confirm="Go live?" type="button"
                            style="font-size:11px; font-weight:600; color:#fff; padding:5px 12px; border:none; border-radius:8px; background:#059669; cursor:pointer;">Live</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- ════════════════════════════════════════════════════════════════
     5 · SHOWING COUNT + LOAD MORE
════════════════════════════════════════════════════════════════ --}}
@if ($totalCount > 0)
<div style="text-align:center; padding-bottom:40px;">
    <p style="
        font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.18em;
        color:var(--sa-text-subtle); margin:0 0 16px;
    ">Showing {{ $tracks->count() }} of {{ number_format($totalCount) }} Tracks</p>

    @if ($tracks->count() < $totalCount)
        <button wire:click="ckLoadMore" type="button" class="sa-load-btn" style="
            padding:13px 36px;
            border:1.5px solid var(--sa-btn-outline);
            border-radius:40px;
            background:var(--sa-card);
            color:var(--sa-text-body);
            font-size:13px; font-weight:700;
            cursor:pointer; transition:all .2s;
            box-shadow:var(--sa-shadow-sm);
        ">
            <span wire:loading.remove wire:target="ckLoadMore">Load More Content</span>
            <span wire:loading wire:target="ckLoadMore">Loading...</span>
        </button>
    @endif
</div>
@endif

</div>{{-- .sa-root --}}

<style>
.sa-list-row:hover { background:var(--sa-card-hover)!important; }
</style>

</x-filament-panels::page>
