<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════════
     FLASHCARDS CMS  ·  Pixel-perfect  ·  Dark / Light mode
     Design: Header → 3 KPI cards → Deck Inventory table → Pagination
════════════════════════════════════════════════════════════════════ --}}

<style>
/* ── Light tokens ── */
:root {
    --fc-bg:           #f9fafb;
    --fc-card:         #ffffff;
    --fc-card-hover:   #f8fafc;
    --fc-border:       rgba(228,228,231,0.8);
    --fc-border-inner: #f1f5f9;
    --fc-text-h:       #18181b;
    --fc-text-body:    #3f3f46;
    --fc-text-muted:   #71717a;
    --fc-text-subtle:  #a1a1aa;
    --fc-input-bg:     #ffffff;
    --fc-surface:      rgba(248,250,252,0.95);
    --fc-shadow-sm:    0 1px 4px rgba(0,0,0,0.06);
    --fc-shadow-md:    0 4px 18px rgba(0,0,0,0.08);
    --fc-shadow-lg:    0 10px 32px rgba(0,0,0,0.12);
    --fc-kpi-icon-1:   rgba(5,150,105,0.12);
    --fc-kpi-icon-2:   rgba(124,58,237,0.12);
    --fc-kpi-icon-3:   rgba(234,179,8,0.12);
    --fc-table-hdr:    rgba(248,250,252,0.95);
    --fc-row-hover:    rgba(248,250,252,0.9);
    --fc-pill-bg:      rgba(124,58,237,0.08);
    --fc-pill-text:    #7c3aed;
    --fc-pill-border:  rgba(124,58,237,0.2);
    --fc-page-active:  #059669;
    --fc-breadcrumb:   #059669;
}
/* ── Dark tokens ── */
.dark {
    --fc-bg:           #111118;
    --fc-card:         #1c1c27;
    --fc-card-hover:   #22222f;
    --fc-border:       rgba(63,63,70,0.85);
    --fc-border-inner: rgba(39,39,42,0.9);
    --fc-text-h:       #f4f4f5;
    --fc-text-body:    #d4d4d8;
    --fc-text-muted:   #a1a1aa;
    --fc-text-subtle:  #52525b;
    --fc-input-bg:     #27272a;
    --fc-surface:      rgba(24,24,35,0.9);
    --fc-shadow-sm:    0 1px 6px rgba(0,0,0,0.4);
    --fc-shadow-md:    0 4px 20px rgba(0,0,0,0.45);
    --fc-shadow-lg:    0 10px 36px rgba(0,0,0,0.6);
    --fc-kpi-icon-1:   rgba(52,211,153,0.1);
    --fc-kpi-icon-2:   rgba(167,139,250,0.1);
    --fc-kpi-icon-3:   rgba(251,191,36,0.1);
    --fc-table-hdr:    rgba(28,28,39,0.95);
    --fc-row-hover:    rgba(34,34,47,0.9);
    --fc-pill-bg:      rgba(167,139,250,0.12);
    --fc-pill-text:    #a78bfa;
    --fc-pill-border:  rgba(167,139,250,0.25);
    --fc-page-active:  #34d399;
    --fc-breadcrumb:   #34d399;
}

/* ── Filament chrome override ── */
.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

/* ── Root ── */
.fc-root { font-family:'Inter','Manrope',system-ui,-apple-system,sans-serif; max-width:1320px; margin:0 auto; }

/* ── Hovers ── */
.fc-kpi-card { transition:transform .18s, box-shadow .18s; }
.fc-kpi-card:hover { transform:translateY(-2px); box-shadow:var(--fc-shadow-md)!important; }
.fc-row { transition:background .12s; }
.fc-row:hover { background:var(--fc-row-hover)!important; }
.fc-create-btn:hover { background:#047857!important; transform:translateY(-2px); box-shadow:0 8px 28px rgba(5,150,105,.4)!important; }
.fc-sort-btn { background:none; border:none; cursor:pointer; color:var(--fc-text-subtle); display:inline-flex; align-items:center; gap:3px; font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; padding:0; transition:color .15s; }
.fc-sort-btn:hover { color:var(--fc-text-body); }
.fc-page-btn { width:34px; height:34px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; cursor:pointer; border:none; transition:all .15s; }
.fc-filter-input:focus { border-color:#059669!important; box-shadow:0 0 0 3px rgba(5,150,105,.12)!important; outline:none; }
</style>

<div class="fc-root">

{{-- ════════════════════════════════════════════════════════════════
     1 · PAGE HEADER
════════════════════════════════════════════════════════════════ --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:26px; gap:16px;">
    <div>
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
            <span style="font-size:11px; font-weight:600; color:var(--fc-text-subtle);">Content</span>
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--fc-text-subtle)" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="font-size:11px; font-weight:700; color:var(--fc-breadcrumb);">Flashcards</span>
        </div>
        <h1 style="font-size:34px; font-weight:800; color:var(--fc-text-h); margin:0; letter-spacing:-.03em; line-height:1.1;">
            Flashcards
        </h1>
        <p style="font-size:13px; color:var(--fc-text-muted); margin:7px 0 0; font-weight:400;">
            Manage interactive cultural learning decks for children.
        </p>
    </div>

    <a href="{{ $createUrl }}" wire:navigate class="fc-create-btn" style="
        display:inline-flex; align-items:center; gap:9px;
        padding:13px 26px;
        background:#059669; color:#fff;
        font-size:14px; font-weight:700;
        border-radius:14px; text-decoration:none;
        box-shadow:0 4px 18px rgba(5,150,105,.3);
        transition:all .2s; white-space:nowrap; flex-shrink:0; margin-top:6px;
    ">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
        Create Deck
    </a>
</div>

{{-- ════════════════════════════════════════════════════════════════
     2 · KPI STAT CARDS (3 wide horizontal cards)
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-bottom:28px;">

    {{-- Card 1: Active Decks --}}
    <div class="fc-kpi-card" style="
        background:var(--fc-card); border:1px solid var(--fc-border);
        border-radius:22px; padding:24px 26px;
        box-shadow:var(--fc-shadow-sm); position:relative; overflow:hidden;
    ">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:var(--fc-kpi-icon-1); opacity:.5;"></div>
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
            <div style="
                width:46px; height:46px; border-radius:14px;
                background:var(--fc-kpi-icon-1);
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/>
                    <line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
            </div>
            <span style="
                font-size:9px; font-weight:800; color:#059669;
                background:rgba(5,150,105,0.1); padding:4px 10px;
                border-radius:20px; letter-spacing:.06em;
            ">+12% this month</span>
        </div>
        <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--fc-text-subtle); margin:0 0 6px;">Active Decks</p>
        <p style="font-size:38px; font-weight:800; color:var(--fc-text-h); margin:0; letter-spacing:-.04em; line-height:1;">{{ number_format($activeDecks) }}</p>
    </div>

    {{-- Card 2: Total Cards --}}
    <div class="fc-kpi-card" style="
        background:var(--fc-card); border:1px solid var(--fc-border);
        border-radius:22px; padding:24px 26px;
        box-shadow:var(--fc-shadow-sm); position:relative; overflow:hidden;
    ">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:var(--fc-kpi-icon-2); opacity:.5;"></div>
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
            <div style="
                width:46px; height:46px; border-radius:14px;
                background:var(--fc-kpi-icon-2);
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                    <polyline points="2 17 12 22 22 17"/>
                    <polyline points="2 12 12 17 22 12"/>
                </svg>
            </div>
            <span style="
                font-size:9px; font-weight:800; color:#7c3aed;
                background:rgba(124,58,237,0.08); padding:4px 10px;
                border-radius:20px; letter-spacing:.06em; border:1px solid rgba(124,58,237,0.15);
            ">Global Assets</span>
        </div>
        <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--fc-text-subtle); margin:0 0 6px;">Total Cards</p>
        <p style="font-size:38px; font-weight:800; color:var(--fc-text-h); margin:0; letter-spacing:-.04em; line-height:1;">{{ number_format($totalCards) }}</p>
    </div>

    {{-- Card 3: Engagement Rate --}}
    <div class="fc-kpi-card" style="
        background:var(--fc-card); border:1px solid var(--fc-border);
        border-radius:22px; padding:24px 26px;
        box-shadow:var(--fc-shadow-sm); position:relative; overflow:hidden;
    ">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:var(--fc-kpi-icon-3); opacity:.5;"></div>
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
            <div style="
                width:46px; height:46px; border-radius:14px;
                background:var(--fc-kpi-icon-3);
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="#ca8a04" stroke="none">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
            </div>
            {{-- Avatar stack --}}
            <div style="display:flex; align-items:center;">
                @php $avatarColors = ['#059669','#7c3aed','#0284c7','#dc2626','#d97706']; @endphp
                @foreach (['P','K','A','S'] as $i => $l)
                    <div style="
                        width:28px; height:28px; border-radius:50%;
                        background:{{ $avatarColors[$i % count($avatarColors)] }};
                        border:2px solid var(--fc-card);
                        margin-left:{{ $i > 0 ? '-8px' : '0' }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:10px; font-weight:800; color:#fff;
                    ">{{ $l }}</div>
                @endforeach
            </div>
        </div>
        <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--fc-text-subtle); margin:0 0 6px;">Engagement Rate</p>
        <p style="font-size:38px; font-weight:800; color:var(--fc-text-h); margin:0; letter-spacing:-.04em; line-height:1;">{{ $engagementFormatted }}</p>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     3 · FILTER BAR
════════════════════════════════════════════════════════════════ --}}
<div style="display:flex; align-items:center; gap:10px; margin-bottom:18px; flex-wrap:wrap;">
    <div style="flex:1; min-width:220px; position:relative;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--fc-text-subtle)" stroke-width="2"
             style="position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none;">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="ckSearch" type="text"
               placeholder="Search decks, tags, or tribes..."
               class="fc-filter-input"
               style="
                   width:100%; box-sizing:border-box;
                   padding:10px 14px 10px 36px;
                   border:1px solid var(--fc-border); border-radius:40px;
                   font-size:13px; font-weight:500;
                   color:var(--fc-text-body); background:var(--fc-input-bg);
                   transition:border-color .15s, box-shadow .15s;
               ">
    </div>
    <select wire:model.live="ckStatus" class="fc-filter-input" style="
        padding:10px 14px; border:1px solid var(--fc-border); border-radius:12px;
        font-size:13px; font-weight:600; color:var(--fc-text-body); background:var(--fc-input-bg); min-width:130px;
    ">
        <option value="">All Status</option>
        <option value="live">Live</option>
        <option value="draft">Draft</option>
        <option value="archived">Archived</option>
    </select>
    <select wire:model.live="ckTribe" class="fc-filter-input" style="
        padding:10px 14px; border:1px solid var(--fc-border); border-radius:12px;
        font-size:13px; font-weight:600; color:var(--fc-text-body); background:var(--fc-input-bg); min-width:130px;
    ">
        <option value="">All Tribes</option>
        @foreach ($tribes as $tribe)
            <option value="{{ $tribe->id }}">{{ $tribe->name }}</option>
        @endforeach
    </select>
</div>

{{-- ════════════════════════════════════════════════════════════════
     4 · DECK INVENTORY TABLE
════════════════════════════════════════════════════════════════ --}}
<div style="
    background:var(--fc-card); border:1px solid var(--fc-border);
    border-radius:24px; overflow:hidden;
    box-shadow:var(--fc-shadow-md);
    margin-bottom:32px;
">
    {{-- Table header bar --}}
    <div style="display:flex; align-items:center; justify-content:space-between; padding:20px 24px; border-bottom:1px solid var(--fc-border-inner);">
        <h2 style="font-size:18px; font-weight:800; color:var(--fc-text-h); margin:0; letter-spacing:-.02em;">Deck Inventory</h2>
        <div style="display:flex; gap:10px;">
            {{-- Filter icon --}}
            <button type="button" style="
                width:34px; height:34px; border-radius:9px; border:1px solid var(--fc-border);
                background:var(--fc-card); cursor:pointer; display:flex; align-items:center; justify-content:center;
                color:var(--fc-text-muted); transition:all .15s;
            ">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
                </svg>
            </button>
            {{-- Sort icon --}}
            <button type="button" style="
                width:34px; height:34px; border-radius:9px; border:1px solid var(--fc-border);
                background:var(--fc-card); cursor:pointer; display:flex; align-items:center; justify-content:center;
                color:var(--fc-text-muted); transition:all .15s;
            ">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="9" x2="21" y2="9"/><path d="m8 4-5 5 5 5"/><line x1="3" y1="15" x2="21" y2="15"/><path d="m16 20 5-5-5-5"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Column headers --}}
    <div style="
        display:grid;
        grid-template-columns: 260px 120px 110px 100px 130px 100px 60px;
        gap:0; padding:12px 24px;
        background:var(--fc-table-hdr);
        border-bottom:1px solid var(--fc-border-inner);
        align-items:center;
    ">
        <button wire:click="ckSort('name')" type="button" class="fc-sort-btn">
            Deck Name
            <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                @if ($ckSort === 'name' && $ckSortDir === 'asc')
                    <polyline points="18 15 12 9 6 15"/>
                @else
                    <polyline points="6 9 12 15 18 9"/>
                @endif
            </svg>
        </button>
        <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--fc-text-subtle);">Tribe</span>
        <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--fc-text-subtle);">Age Group</span>
        <button wire:click="ckSort('cards')" type="button" class="fc-sort-btn">
            Cards
            <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                @if ($ckSort === 'cards' && $ckSortDir === 'asc')
                    <polyline points="18 15 12 9 6 15"/>
                @else
                    <polyline points="6 9 12 15 18 9"/>
                @endif
            </svg>
        </button>
        <button wire:click="ckSort('updated_at')" type="button" class="fc-sort-btn">
            Last Updated
            <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                @if ($ckSort === 'updated_at' && $ckSortDir === 'asc')
                    <polyline points="18 15 12 9 6 15"/>
                @else
                    <polyline points="6 9 12 15 18 9"/>
                @endif
            </svg>
        </button>
        <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--fc-text-subtle);">Status</span>
        <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--fc-text-subtle);">Action</span>
    </div>

    {{-- Rows --}}
    @forelse ($decks as $deck)
    @php
        $cover = $deck->cover_image_path
            ? \Illuminate\Support\Facades\Storage::url($deck->cover_image_path)
            : null;
        // Avatar gradient fallbacks
        $avatarGrads = [
            'linear-gradient(135deg,#059669,#34d399)',
            'linear-gradient(135deg,#0284c7,#38bdf8)',
            'linear-gradient(135deg,#7c3aed,#a78bfa)',
            'linear-gradient(135deg,#dc2626,#f87171)',
            'linear-gradient(135deg,#d97706,#fbbf24)',
            'linear-gradient(135deg,#0891b2,#22d3ee)',
        ];
        $grad = $avatarGrads[$deck->id % count($avatarGrads)];
        $initials = strtoupper(substr($deck->name, 0, 2));

        // Status config
        $sc = match($deck->status) {
            'live'     => ['Live',     '#059669', 'rgba(5,150,105,0.1)'],
            'draft'    => ['Draft',    '#d97706', 'rgba(217,119,6,0.1)'],
            'archived' => ['Archived', '#6b7280', 'rgba(107,114,128,0.1)'],
            default    => [ucfirst($deck->status), '#6b7280', 'rgba(107,114,128,0.1)'],
        };

        // Tribe pill color
        $tribeName = $deck->tribe?->name ?? null;
    @endphp
    <div class="fc-row" style="
        display:grid;
        grid-template-columns:260px 120px 110px 100px 130px 100px 60px;
        gap:0; padding:18px 24px;
        align-items:center;
        border-bottom:1px solid var(--fc-border-inner);
    ">
        {{-- Deck Name + Avatar --}}
        <div style="display:flex; align-items:center; gap:12px; min-width:0;">
            <div style="
                width:44px; height:44px; border-radius:50%; flex-shrink:0; overflow:hidden;
                background:{{ $grad }};
                display:flex; align-items:center; justify-content:center;
                font-size:13px; font-weight:800; color:#fff;
                box-shadow:0 2px 8px rgba(0,0,0,0.15);
            ">
                @if ($cover)
                    <img src="{{ $cover }}" style="width:100%; height:100%; object-fit:cover;" alt="{{ $deck->name }}">
                @else
                    {{ $initials }}
                @endif
            </div>
            <div style="min-width:0;">
                <p style="font-size:15px; font-weight:700; color:var(--fc-text-h); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $deck->name }}
                </p>
                @if ($deck->subtitle)
                    <p style="font-size:11px; color:var(--fc-text-subtle); margin:2px 0 0;">{{ $deck->subtitle }}</p>
                @endif
            </div>
        </div>

        {{-- Tribe pill --}}
        <div>
            @if ($tribeName)
                <span style="
                    display:inline-block; padding:4px 12px;
                    background:var(--fc-pill-bg); color:var(--fc-pill-text);
                    border:1px solid var(--fc-pill-border);
                    border-radius:20px; font-size:11px; font-weight:700;
                ">{{ $tribeName }}</span>
            @else
                <span style="color:var(--fc-text-subtle); font-size:12px;">—</span>
            @endif
        </div>

        {{-- Age group --}}
        <div>
            <p style="font-size:13px; font-weight:600; color:var(--fc-text-body); margin:0;">{{ $deck->age_min }}-{{ $deck->age_max }} Years</p>
        </div>

        {{-- Cards count --}}
        <div>
            <p style="font-size:13px; font-weight:600; color:var(--fc-text-body); margin:0;">{{ number_format($deck->cards_count) }}</p>
            <p style="font-size:10px; color:var(--fc-text-subtle); margin:2px 0 0;">Cards</p>
        </div>

        {{-- Last updated --}}
        <div>
            <p style="font-size:13px; font-weight:500; color:var(--fc-text-body); margin:0;">{{ $deck->updated_at->format('M d, Y') }}</p>
        </div>

        {{-- Status --}}
        <div>
            <div style="display:inline-flex; align-items:center; gap:5px;">
                <div style="width:7px; height:7px; border-radius:50%; background:{{ $sc[1] }};"></div>
                <span style="font-size:13px; font-weight:600; color:{{ $sc[1] }};">{{ $sc[0] }}</span>
            </div>
        </div>

        {{-- Action 3-dot menu --}}
        <div style="position:relative;">
            <div x-data="{ open: false }" style="position:relative;">
                <button type="button" @click="open = !open"
                        style="width:32px; height:32px; border-radius:50%; border:none; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--fc-text-muted);"
                        title="Actions">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open=false"
                     style="
                         position:absolute; right:0; top:36px; z-index:50;
                         background:var(--fc-card); border:1px solid var(--fc-border);
                         border-radius:14px; padding:6px;
                         box-shadow:var(--fc-shadow-lg); min-width:140px;
                     ">
                    <a href="{{ \App\Filament\Resources\FlashcardDeckResource::getUrl('edit', ['record' => $deck]) }}" wire:navigate
                       style="display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:9px; font-size:13px; font-weight:600; color:var(--fc-text-body); text-decoration:none; transition:background .12s;"
                       onmouseover="this.style.background='var(--fc-row-hover)'" onmouseout="this.style.background='transparent'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit Deck
                    </a>
                    @if ($deck->status !== 'live')
                    <button type="button" wire:click="ckPublish({{ $deck->id }})" wire:confirm="Publish '{{ $deck->name }}'?"
                            style="width:100%; display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:9px; font-size:13px; font-weight:600; color:#059669; background:transparent; border:none; cursor:pointer; text-align:left; transition:background .12s;"
                            onmouseover="this.style.background='var(--fc-row-hover)'" onmouseout="this.style.background='transparent'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Publish
                    </button>
                    @endif
                    @if ($deck->status === 'live')
                    <button type="button" wire:click="ckArchive({{ $deck->id }})" wire:confirm="Archive '{{ $deck->name }}'?"
                            style="width:100%; display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:9px; font-size:13px; font-weight:600; color:#d97706; background:transparent; border:none; cursor:pointer; text-align:left; transition:background .12s;"
                            onmouseover="this.style.background='var(--fc-row-hover)'" onmouseout="this.style.background='transparent'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                        Archive
                    </button>
                    @endif
                    <div style="height:1px; background:var(--fc-border-inner); margin:4px 6px;"></div>
                    <button type="button" wire:click="ckDelete({{ $deck->id }})" wire:confirm="Permanently delete '{{ $deck->name }}'? This cannot be undone."
                            style="width:100%; display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:9px; font-size:13px; font-weight:600; color:#dc2626; background:transparent; border:none; cursor:pointer; text-align:left; transition:background .12s;"
                            onmouseover="this.style.background='rgba(220,38,38,0.06)'" onmouseout="this.style.background='transparent'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:60px 20px;">
        <p style="font-size:40px; margin:0 0 14px;">📋</p>
        <h3 style="font-size:16px; font-weight:700; color:var(--fc-text-muted); margin:0 0 8px;">No decks found</h3>
        <p style="font-size:13px; color:var(--fc-text-subtle); margin:0 0 20px;">Create your first flashcard deck to get started.</p>
        <a href="{{ $createUrl }}" wire:navigate style="
            display:inline-flex; align-items:center; gap:8px; padding:11px 24px;
            background:#059669; color:#fff; font-size:13px; font-weight:700;
            border-radius:12px; text-decoration:none;
        ">+ Create Deck</a>
    </div>
    @endforelse

    {{-- ── PAGINATION FOOTER ── --}}
    <div style="
        display:flex; align-items:center; justify-content:space-between;
        padding:16px 24px;
        border-top:1px solid var(--fc-border-inner);
    ">
        <p style="font-size:12px; font-weight:500; color:var(--fc-text-muted); margin:0;">
            Showing {{ ($currentPage - 1) * $perPage + 1 }} to {{ min($currentPage * $perPage, $totalCount) }} of {{ number_format($totalCount) }} decks
        </p>

        <div style="display:flex; align-items:center; gap:6px;">
            {{-- Prev --}}
            <button type="button" wire:click="ckSetPage({{ max(1, $currentPage - 1) }})"
                    class="fc-page-btn"
                    style="background:{{ $currentPage <= 1 ? 'transparent' : 'var(--fc-card)' }}; border:1px solid var(--fc-border); color:var(--fc-text-muted); {{ $currentPage <= 1 ? 'opacity:.4; cursor:not-allowed;' : 'cursor:pointer;' }}"
                    {{ $currentPage <= 1 ? 'disabled' : '' }}>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            {{-- Page numbers --}}
            @php
                $showPages = [];
                for ($p = max(1, $currentPage - 1); $p <= min($totalPages, $currentPage + 2); $p++) {
                    $showPages[] = $p;
                }
            @endphp
            @foreach ($showPages as $p)
                <button type="button" wire:click="ckSetPage({{ $p }})"
                        class="fc-page-btn"
                        style="
                            background:{{ $p === $currentPage ? 'var(--fc-page-active)' : 'var(--fc-card)' }};
                            color:{{ $p === $currentPage ? '#fff' : 'var(--fc-text-body)' }};
                            border:1px solid {{ $p === $currentPage ? 'var(--fc-page-active)' : 'var(--fc-border)' }};
                            font-weight:{{ $p === $currentPage ? '800' : '600' }};
                        ">{{ $p }}</button>
            @endforeach

            {{-- Next --}}
            <button type="button" wire:click="ckSetPage({{ min($totalPages, $currentPage + 1) }})"
                    class="fc-page-btn"
                    style="background:{{ $currentPage >= $totalPages ? 'transparent' : 'var(--fc-card)' }}; border:1px solid var(--fc-border); color:var(--fc-text-muted); {{ $currentPage >= $totalPages ? 'opacity:.4; cursor:not-allowed;' : 'cursor:pointer;' }}"
                    {{ $currentPage >= $totalPages ? 'disabled' : '' }}>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>
</div>

</div>{{-- .fc-root --}}
</x-filament-panels::page>
