<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════════
     COMICS CMS  ·  Pixel-perfect · Dark / Light mode
     Layout: Header → Featured Cards Row → Global Performance + Insight
════════════════════════════════════════════════════════════════════ --}}

<style>
/* ── Light tokens ── */
:root {
    --ck-card:         #ffffff;
    --ck-card-hover:   #f8fafc;
    --ck-border:       rgba(228,228,231,0.75);
    --ck-border-inner: #f1f5f9;
    --ck-text-h:       #18181b;
    --ck-text-body:    #3f3f46;
    --ck-text-muted:   #71717a;
    --ck-text-subtle:  #a1a1aa;
    --ck-input-bg:     #ffffff;
    --ck-surface:      rgba(248,250,252,0.9);
    --ck-shadow-sm:    0 1px 4px rgba(0,0,0,0.06);
    --ck-shadow-md:    0 4px 16px rgba(0,0,0,0.08);
    --ck-shadow-hover: 0 10px 32px rgba(0,0,0,0.12);
    --ck-stat-bg:      rgba(248,250,252,0.85);
    --ck-preview-bg:   #f9fafb;
    --ck-chart-bg:     #ffffff;
    --ck-chart-bar:    rgba(5,150,105,0.08);
    --ck-empty-bg:     rgba(248,250,252,0.6);
}
/* ── Dark tokens ── */
.dark {
    --ck-card:         #1c1c27;
    --ck-card-hover:   #22222f;
    --ck-border:       rgba(63,63,70,0.8);
    --ck-border-inner: rgba(39,39,42,0.9);
    --ck-text-h:       #f4f4f5;
    --ck-text-body:    #d4d4d8;
    --ck-text-muted:   #a1a1aa;
    --ck-text-subtle:  #52525b;
    --ck-input-bg:     #27272a;
    --ck-surface:      rgba(24,24,35,0.85);
    --ck-shadow-sm:    0 1px 6px rgba(0,0,0,0.4);
    --ck-shadow-md:    0 4px 20px rgba(0,0,0,0.45);
    --ck-shadow-hover: 0 10px 36px rgba(0,0,0,0.6);
    --ck-stat-bg:      rgba(30,30,42,0.85);
    --ck-preview-bg:   rgba(30,30,42,0.7);
    --ck-chart-bg:     #1c1c27;
    --ck-chart-bar:    rgba(52,211,153,0.06);
    --ck-empty-bg:     rgba(28,28,39,0.6);
}

/* ── Filament chrome override ── */
.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

/* ── Root ── */
.ck-cms-root { font-family:'Inter','Manrope',system-ui,-apple-system,sans-serif; max-width:1320px; margin:0 auto; }

/* ── Transitions ── */
.ck-comic-card{ transition: transform 0.22s ease, box-shadow 0.22s ease; }
.ck-comic-card:hover{ transform:translateY(-5px); box-shadow:var(--ck-shadow-hover)!important; }
.ck-comic-card:hover .ck-card-overlay{ opacity:1!important; }

.ck-new-btn:hover{
    background:#047857!important;
    transform:translateY(-2px);
    box-shadow:0 8px 28px rgba(5,150,105,0.45)!important;
}
.ck-filter-input:focus{ border-color:#059669!important; box-shadow:0 0 0 3px rgba(5,150,105,0.12)!important; outline:none; }
.ck-stat-card:hover{ transform:translateY(-2px); box-shadow:var(--ck-shadow-md)!important; }
.ck-tab-btn{ transition:all .15s; }
.ck-action-pill{ transition:all .15s; }
.ck-action-pill:hover{ transform:translateY(-1px); }
</style>

<div class="ck-cms-root">

{{-- ════════════════════════════════════════════════════════════════
     1 · PAGE HEADER
════════════════════════════════════════════════════════════════ --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:6px; gap:16px;">
    <div>
        <h1 style="font-size:32px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.03em; line-height:1.1;">
            Comics CMS
        </h1>
        <p style="font-size:13px; color:var(--ck-text-muted); margin:6px 0 0; font-weight:400;">
            Manage and curate your digital library of cultural stories.
        </p>
    </div>

    <a href="{{ $createUrl }}" wire:navigate class="ck-new-btn" style="
        display:inline-flex; align-items:center; gap:8px;
        padding:12px 24px;
        background:#059669; color:#fff;
        font-size:14px; font-weight:700;
        border-radius:40px; text-decoration:none;
        box-shadow:0 4px 18px rgba(5,150,105,.35);
        transition:all .2s; white-space:nowrap; flex-shrink:0; margin-top:4px;
    ">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Comic
    </a>
</div>

{{-- ════════════════════════════════════════════════════════════════
     2 · KPI STAT STRIP
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin:20px 0 24px;">
    @php
        $stats = [
            ['label'=>'Total Comics',  'value'=>$totalComics,              'icon'=>'📚', 'color'=>'#059669'],
            ['label'=>'Published',     'value'=>$publishedCount,           'icon'=>'✅', 'color'=>'#059669'],
            ['label'=>'In Draft',      'value'=>$draftCount,               'icon'=>'✏️', 'color'=>'#d97706'],
            ['label'=>'Pending Review','value'=>$reviewCount,              'icon'=>'🔍', 'color'=>'#7c3aed'],
        ];
    @endphp
    @foreach ($stats as $s)
        <div class="ck-stat-card" style="
            background:var(--ck-card);
            border:1px solid var(--ck-border);
            border-radius:18px; padding:16px 18px;
            box-shadow:var(--ck-shadow-sm);
            transition:transform .2s, box-shadow .2s;
            display:flex; align-items:center; gap:12px;
        ">
            <span style="font-size:22px; line-height:1;">{{ $s['icon'] }}</span>
            <div>
                <p style="font-size:22px; font-weight:800; color:{{ $s['color'] }}; margin:0; letter-spacing:-.03em; line-height:1.1;">{{ number_format($s['value']) }}</p>
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle); margin:3px 0 0 0;">{{ $s['label'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- ════════════════════════════════════════════════════════════════
     3 · SEARCH / FILTER BAR
════════════════════════════════════════════════════════════════ --}}
<div style="display:flex; align-items:center; gap:10px; margin-bottom:24px; flex-wrap:wrap;">

    <div style="flex:1; min-width:200px; position:relative;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--ck-text-subtle)" stroke-width="2"
             style="position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none;">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="ckSearch" type="text"
               placeholder="Search comics, creators..."
               class="ck-filter-input"
               style="
                   width:100%; box-sizing:border-box;
                   padding:10px 14px 10px 36px;
                   border:1px solid var(--ck-border); border-radius:12px;
                   font-size:13px; font-weight:500;
                   color:var(--ck-text-body); background:var(--ck-input-bg);
                   transition:border-color .15s, box-shadow .15s;
               ">
    </div>

    {{-- Filter button (status) --}}
    <select wire:model.live="ckStatusFilter" class="ck-filter-input" style="
        padding:10px 14px; border:1px solid var(--ck-border);
        border-radius:12px; font-size:13px; font-weight:600;
        color:var(--ck-text-body); background:var(--ck-input-bg); min-width:130px;
    ">
        <option value="">All Status</option>
        <option value="published">Published</option>
        <option value="draft">Draft</option>
        <option value="review">In Review</option>
        <option value="archived">Archived</option>
    </select>

    <select wire:model.live="ckTribeFilter" class="ck-filter-input" style="
        padding:10px 14px; border:1px solid var(--ck-border);
        border-radius:12px; font-size:13px; font-weight:600;
        color:var(--ck-text-body); background:var(--ck-input-bg); min-width:130px;
    ">
        <option value="">All Tribes</option>
        @foreach ($tribes as $tribe)
            <option value="{{ $tribe->id }}">{{ $tribe->name }}</option>
        @endforeach
    </select>

    {{-- View toggle --}}
    <div style="display:flex; gap:4px; background:var(--ck-surface); border:1px solid var(--ck-border); border-radius:12px; padding:4px;">
        <button wire:click="ckSetView('grid')" type="button" class="ck-tab-btn" style="
            padding:7px 12px; border:none; cursor:pointer; border-radius:9px; font-size:12px; font-weight:700;
            background:{{ $ckView === 'grid' ? '#059669' : 'transparent' }};
            color:{{ $ckView === 'grid' ? '#fff' : 'var(--ck-text-muted)' }};
        ">⊞ Grid</button>
        <button wire:click="ckSetView('table')" type="button" class="ck-tab-btn" style="
            padding:7px 12px; border:none; cursor:pointer; border-radius:9px; font-size:12px; font-weight:700;
            background:{{ $ckView === 'table' ? '#059669' : 'transparent' }};
            color:{{ $ckView === 'table' ? '#fff' : 'var(--ck-text-muted)' }};
        ">☰ List</button>
    </div>

    @if ($ckSearch || $ckStatusFilter || $ckTribeFilter)
        <button wire:click="ckResetFilters" type="button" style="
            padding:10px 14px; font-size:13px; font-weight:700;
            color:#059669; border:none; background:transparent; cursor:pointer;
        ">↺ Reset</button>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════════
     4 · FEATURED COMIC CARDS ROW (3-col: card card CTA)
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:28px;">

    {{-- ── Featured comic cards (first 2) ── --}}
    @forelse ($featured->take(2) as $idx => $comic)
    @php
        $cover  = $comic->cover_image_path ? \Illuminate\Support\Facades\Storage::url($comic->cover_image_path) : null;
        $status = $comic->status;
        $statusConfig = match($status) {
            'published' => ['label'=>'LIVE',      'bg'=>'#059669', 'text'=>'#fff'],
            'draft'     => ['label'=>'DRAFT',     'bg'=>'#1c1c27', 'text'=>'#f59e0b'],
            'review'    => ['label'=>'IN REVIEW', 'bg'=>'#7c3aed', 'text'=>'#fff'],
            'archived'  => ['label'=>'ARCHIVED',  'bg'=>'#6b7280', 'text'=>'#fff'],
            default     => ['label'=>strtoupper($status),'bg'=>'#6b7280','text'=>'#fff'],
        };
        $tribeName = $comic->tribe?->name ?? 'Unknown Tribe';
        $tribeColor = match(true) {
            str_contains(strtolower($tribeName), 'igbo')   => '#059669',
            str_contains(strtolower($tribeName), 'yoruba') => '#7c3aed',
            str_contains(strtolower($tribeName), 'zulu')   => '#dc2626',
            str_contains(strtolower($tribeName), 'maasai') => '#d97706',
            default => '#059669',
        };
        $ageLabel = $comic->age_min && $comic->age_max ? $comic->age_min . '-' . $comic->age_max . ' YEARS' : '';
        // Simulated stats
        $views     = $status === 'published' ? number_format(rand(10,500)/10 * 1000, 1) . 'k' : '0';
        $downloads = $status === 'published' ? number_format(rand(1,50)/10 * 1000, 1) . 'k' : '0';

        // Gradient fallback cover colors
        $coverGrads = [
            'linear-gradient(160deg,#1a1a2e 0%,#16213e 40%,#0f3460 100%)',
            'linear-gradient(160deg,#2d0000 0%,#5c0000 40%,#800000 100%)',
            'linear-gradient(160deg,#001233 0%,#023e8a 50%,#0077b6 100%)',
            'linear-gradient(160deg,#1b1b2f 0%,#2e4057 40%,#048a81 100%)',
        ];
        $coverGrad = $coverGrads[($comic->id ?? 0) % count($coverGrads)];
    @endphp

    <div class="ck-comic-card" style="
        background:var(--ck-card);
        border:1px solid var(--ck-border);
        border-radius:20px;
        box-shadow:var(--ck-shadow-md);
        overflow:hidden;
        position:relative;
    ">
        {{-- Cover image area --}}
        <div style="
            position:relative;
            height:260px;
            background:{{ $coverGrad }};
            overflow:hidden;
        ">
            @if ($cover)
                <img src="{{ $cover }}" alt="{{ $comic->title }}" style="
                    width:100%; height:100%; object-fit:cover;
                    transition:transform .3s ease;
                " class="ck-cover-img">
            @else
                {{-- Stylised placeholder with title text overlay --}}
                <div style="
                    width:100%; height:100%;
                    display:flex; align-items:center; justify-content:center;
                    flex-direction:column; gap:8px;
                ">
                    <span style="font-size:52px; line-height:1; filter:drop-shadow(0 0 20px rgba(255,255,255,0.3));">📖</span>
                    <p style="font-size:13px; font-weight:700; color:rgba(255,255,255,0.6); text-align:center; padding:0 16px; margin:0;">{{ $comic->title }}</p>
                </div>
            @endif

            {{-- Status badge top-right --}}
            <span style="
                position:absolute; top:12px; right:12px;
                padding:4px 10px; border-radius:20px;
                font-size:9px; font-weight:800; letter-spacing:.1em;
                background:{{ $statusConfig['bg'] }}; color:{{ $statusConfig['text'] }};
                box-shadow:0 2px 8px rgba(0,0,0,0.3);
            ">{{ $statusConfig['label'] }}</span>

            {{-- Hover overlay with actions --}}
            <div class="ck-card-overlay" style="
                position:absolute; inset:0;
                background:rgba(0,0,0,0.6);
                display:flex; align-items:center; justify-content:center; gap:10px;
                opacity:0; transition:opacity .2s;
            ">
                <a href="{{ \App\Filament\Resources\ComicResource::getUrl('edit', ['record' => $comic]) }}"
                   wire:navigate
                   style="
                       display:inline-flex; align-items:center; gap:6px;
                       padding:9px 18px; background:#fff; color:#18181b;
                       font-size:12px; font-weight:700; border-radius:30px;
                       text-decoration:none; box-shadow:0 2px 8px rgba(0,0,0,0.3);
                   ">✏️ Edit</a>
                @if ($comic->status !== 'published')
                    <button type="button" wire:click="ckPublishComic({{ $comic->id }})"
                            wire:confirm="Publish '{{ $comic->title }}'?"
                            style="
                                display:inline-flex; align-items:center; gap:6px;
                                padding:9px 18px; background:#059669; color:#fff;
                                font-size:12px; font-weight:700; border-radius:30px;
                                border:none; cursor:pointer; box-shadow:0 2px 8px rgba(5,150,105,0.4);
                            ">✅ Publish</button>
                @endif
            </div>
        </div>

        {{-- Card body --}}
        <div style="padding:16px 18px;">
            {{-- Tags row --}}
            <div style="display:flex; align-items:center; gap:6px; margin-bottom:10px; flex-wrap:wrap;">
                <span style="
                    padding:3px 10px; border-radius:20px; font-size:9px; font-weight:800;
                    text-transform:uppercase; letter-spacing:.08em;
                    background:{{ $tribeColor }}18; color:{{ $tribeColor }};
                    border:1px solid {{ $tribeColor }}30;
                ">{{ strtoupper($tribeName) }}</span>
                @if ($ageLabel)
                    <span style="
                        padding:3px 10px; border-radius:20px; font-size:9px; font-weight:800;
                        text-transform:uppercase; letter-spacing:.08em;
                        background:var(--ck-border-inner); color:var(--ck-text-muted);
                        border:1px solid var(--ck-border);
                    ">{{ $ageLabel }}</span>
                @endif
            </div>

            <h3 style="font-size:17px; font-weight:800; color:var(--ck-text-h); margin:0 0 14px; letter-spacing:-.02em; line-height:1.3;">
                {{ $comic->title }}
            </h3>

            {{-- Stats row --}}
            <div style="display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--ck-border-inner); padding-top:12px;">
                <div style="display:flex; align-items:center; gap:5px; color:var(--ck-text-subtle);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span style="font-size:13px; font-weight:700; color:var(--ck-text-body);">{{ $views }}</span>
                </div>
                <div style="display:flex; align-items:center; gap:5px; color:var(--ck-text-subtle);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    <span style="font-size:13px; font-weight:700; color:var(--ck-text-body);">{{ $downloads }}</span>
                </div>
            </div>
        </div>
    </div>
    @empty
    @endforelse

    {{-- ── CTA / Empty slot card ── --}}
    <div style="
        background:var(--ck-empty-bg);
        border:1.5px dashed var(--ck-border);
        border-radius:20px;
        display:flex; flex-direction:column;
        align-items:center; justify-content:center;
        padding:32px 24px; text-align:center;
        min-height:380px;
        box-shadow:var(--ck-shadow-sm);
    ">
        <div style="
            width:60px; height:60px; border-radius:50%;
            background:var(--ck-card);
            border:1px solid var(--ck-border);
            display:flex; align-items:center; justify-content:center;
            margin-bottom:18px;
            box-shadow:var(--ck-shadow-sm);
        ">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
        </div>
        <h3 style="font-size:18px; font-weight:800; color:var(--ck-text-h); margin:0 0 10px; line-height:1.35; letter-spacing:-.02em;">
            Start your journey<br>as a curator
        </h3>
        <p style="font-size:12px; color:var(--ck-text-muted); line-height:1.65; margin:0 0 20px; max-width:200px;">
            Add your first cultural comic and share the heritage with children around the globe.
        </p>
        <a href="{{ $createUrl }}" wire:navigate style="
            display:inline-flex; align-items:center; gap:6px;
            font-size:13px; font-weight:700; color:#059669;
            text-decoration:none; transition:gap .15s;
        " class="ck-cta-link">
            Create your first comic
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     5 · FULL COMICS GRID (all filtered results)
════════════════════════════════════════════════════════════════ --}}
@if ($comics->count() > 0)
<div style="margin-bottom:28px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
        <h2 style="font-size:18px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.02em;">
            All Comics
            <span style="font-size:13px; font-weight:500; color:var(--ck-text-muted); margin-left:8px;">({{ $comics->count() }})</span>
        </h2>
    </div>

    @if ($ckView === 'grid')
        {{-- ── GRID VIEW ── --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">
            @foreach ($comics as $comic)
            @php
                $cover  = $comic->cover_image_path ? \Illuminate\Support\Facades\Storage::url($comic->cover_image_path) : null;
                $sc = match($comic->status) {
                    'published' => ['label'=>'LIVE',      'bg'=>'#059669','text'=>'#fff'],
                    'draft'     => ['label'=>'DRAFT',     'bg'=>'rgba(28,28,39,0.85)','text'=>'#f59e0b'],
                    'review'    => ['label'=>'IN REVIEW', 'bg'=>'#7c3aed','text'=>'#fff'],
                    'archived'  => ['label'=>'ARCHIVED',  'bg'=>'#6b7280','text'=>'#fff'],
                    default     => ['label'=>strtoupper($comic->status),'bg'=>'#6b7280','text'=>'#fff'],
                };
                $coverGrads = [
                    'linear-gradient(160deg,#1a1a2e 0%,#0f3460 100%)',
                    'linear-gradient(160deg,#2d0000 0%,#800000 100%)',
                    'linear-gradient(160deg,#001233 0%,#0077b6 100%)',
                    'linear-gradient(160deg,#1b1b2f 0%,#048a81 100%)',
                    'linear-gradient(160deg,#2c1810 0%,#8b4513 100%)',
                    'linear-gradient(160deg,#0a0a23 0%,#4b0082 100%)',
                ];
                $cg = $coverGrads[$comic->id % count($coverGrads)];
                $tn = $comic->tribe?->name ?? '—';
                $tcol = match(true) {
                    str_contains(strtolower($tn),'igbo')   => '#059669',
                    str_contains(strtolower($tn),'yoruba') => '#7c3aed',
                    str_contains(strtolower($tn),'zulu')   => '#dc2626',
                    str_contains(strtolower($tn),'maasai') => '#d97706',
                    default => '#059669',
                };
            @endphp
            <div class="ck-comic-card" style="
                background:var(--ck-card); border:1px solid var(--ck-border);
                border-radius:16px; overflow:hidden;
                box-shadow:var(--ck-shadow-sm); position:relative;
            ">
                <div style="position:relative; height:180px; background:{{ $cg }}; overflow:hidden;">
                    @if ($cover)
                        <img src="{{ $cover }}" alt="{{ $comic->title }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                            <span style="font-size:36px; opacity:.6;">📖</span>
                        </div>
                    @endif
                    <span style="
                        position:absolute; top:8px; right:8px;
                        padding:3px 9px; border-radius:20px;
                        font-size:8px; font-weight:800; letter-spacing:.1em;
                        background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};
                    ">{{ $sc['label'] }}</span>
                    <div class="ck-card-overlay" style="
                        position:absolute; inset:0; background:rgba(0,0,0,0.65);
                        display:flex; align-items:center; justify-content:center; gap:8px;
                        opacity:0; transition:opacity .2s;
                    ">
                        <a href="{{ \App\Filament\Resources\ComicResource::getUrl('edit', ['record' => $comic]) }}" wire:navigate
                           style="padding:7px 14px; background:#fff; color:#18181b; font-size:11px; font-weight:700; border-radius:20px; text-decoration:none;">Edit</a>
                        @if ($comic->status !== 'published')
                        <button wire:click="ckPublishComic({{ $comic->id }})" wire:confirm="Publish?" type="button"
                                style="padding:7px 14px; background:#059669; color:#fff; font-size:11px; font-weight:700; border-radius:20px; border:none; cursor:pointer;">Publish</button>
                        @endif
                    </div>
                </div>
                <div style="padding:12px 14px;">
                    <span style="font-size:8px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:{{ $tcol }}; background:{{ $tcol }}15; padding:2px 8px; border-radius:10px; border:1px solid {{ $tcol }}25;">{{ strtoupper($tn) }}</span>
                    <h4 style="font-size:13px; font-weight:700; color:var(--ck-text-h); margin:8px 0 4px; line-height:1.3; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $comic->title }}</h4>
                    <p style="font-size:11px; color:var(--ck-text-muted); margin:0;">Ages {{ $comic->age_min }}–{{ $comic->age_max }}</p>
                </div>
            </div>
            @endforeach
        </div>

    @else
        {{-- ── LIST / TABLE VIEW ── --}}
        <div style="background:var(--ck-card); border:1px solid var(--ck-border); border-radius:18px; overflow:hidden; box-shadow:var(--ck-shadow-sm);">
            <div style="display:grid; grid-template-columns:48px 1fr 160px 110px 100px 120px; gap:0; padding:12px 20px; background:var(--ck-surface); border-bottom:1px solid var(--ck-border); font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle);">
                <span></span><span>Comic</span><span>Tribe</span><span>Age Range</span><span>Status</span><span style="text-align:right;">Actions</span>
            </div>
            @foreach ($comics as $comic)
            @php
                $cover = $comic->cover_image_path ? \Illuminate\Support\Facades\Storage::url($comic->cover_image_path) : null;
                $sc = match($comic->status) {
                    'published' => ['label'=>'Live',     'c'=>'#059669','bg'=>'rgba(5,150,105,0.1)'],
                    'draft'     => ['label'=>'Draft',    'c'=>'#d97706','bg'=>'rgba(217,119,6,0.1)'],
                    'review'    => ['label'=>'Review',   'c'=>'#7c3aed','bg'=>'rgba(124,58,237,0.1)'],
                    'archived'  => ['label'=>'Archived', 'c'=>'#6b7280','bg'=>'rgba(107,114,128,0.1)'],
                    default     => ['label'=>ucfirst($comic->status),'c'=>'#6b7280','bg'=>'rgba(107,114,128,0.1)'],
                };
            @endphp
            <div style="display:grid; grid-template-columns:48px 1fr 160px 110px 100px 120px; gap:0; padding:14px 20px; align-items:center; border-bottom:1px solid var(--ck-border-inner); transition:background .15s;" class="ck-list-row">
                <div>
                    <div style="width:38px; height:52px; border-radius:8px; overflow:hidden; background:var(--ck-surface);">
                        @if ($cover)
                            <img src="{{ $cover }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:18px;">📖</div>
                        @endif
                    </div>
                </div>
                <div style="min-width:0; padding-right:12px;">
                    <p style="font-size:14px; font-weight:700; color:var(--ck-text-h); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $comic->title }}</p>
                </div>
                <div><span style="font-size:12px; font-weight:600; color:var(--ck-text-body);">{{ $comic->tribe?->name ?? '—' }}</span></div>
                <div><span style="font-size:12px; color:var(--ck-text-muted);">{{ $comic->age_min }}–{{ $comic->age_max }} yrs</span></div>
                <div>
                    <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:700; background:{{ $sc['bg'] }}; color:{{ $sc['c'] }};">{{ $sc['label'] }}</span>
                </div>
                <div style="display:flex; gap:6px; justify-content:flex-end;">
                    <a href="{{ \App\Filament\Resources\ComicResource::getUrl('edit', ['record' => $comic]) }}" wire:navigate
                       style="font-size:11px; font-weight:600; color:var(--ck-text-body); padding:5px 12px; border:1px solid var(--ck-border); border-radius:8px; background:var(--ck-card); text-decoration:none;">Edit</a>
                    @if ($comic->status !== 'published')
                    <button wire:click="ckPublishComic({{ $comic->id }})" wire:confirm="Publish?" type="button"
                            style="font-size:11px; font-weight:600; color:#fff; padding:5px 12px; border:none; border-radius:8px; background:#059669; cursor:pointer;">Publish</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endif

{{-- ════════════════════════════════════════════════════════════════
     6 · BOTTOM ROW: GLOBAL PERFORMANCE CHART + SYSTEM INSIGHT
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:1fr 340px; gap:18px; margin-bottom:40px;">

    {{-- ── GLOBAL PERFORMANCE CHART ── --}}
    <div style="
        background:var(--ck-chart-bg);
        border:1px solid var(--ck-border);
        border-radius:20px; padding:24px;
        box-shadow:var(--ck-shadow-sm);
    ">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
            <h3 style="font-size:18px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.02em;">Global Performance</h3>

            {{-- Tab pills --}}
            <div style="display:flex; gap:4px; background:var(--ck-surface); border:1px solid var(--ck-border); border-radius:10px; padding:4px;">
                <button wire:click="$set('ckChartTab','views')" type="button" class="ck-tab-btn" style="
                    padding:6px 16px; border:none; cursor:pointer; border-radius:7px; font-size:12px; font-weight:700;
                    background:{{ $ckChartTab === 'views' ? '#059669' : 'transparent' }};
                    color:{{ $ckChartTab === 'views' ? '#fff' : 'var(--ck-text-muted)' }};
                ">Views</button>
                <button wire:click="$set('ckChartTab','reads')" type="button" class="ck-tab-btn" style="
                    padding:6px 16px; border:none; cursor:pointer; border-radius:7px; font-size:12px; font-weight:700;
                    background:{{ $ckChartTab === 'reads' ? '#059669' : 'transparent' }};
                    color:{{ $ckChartTab === 'reads' ? '#fff' : 'var(--ck-text-muted)' }};
                ">Reads</button>
            </div>

            {{-- Today pill --}}
            <span style="
                display:inline-block; padding:5px 14px;
                background:#18181b; color:#fff;
                font-size:11px; font-weight:700;
                border-radius:20px; letter-spacing:.04em;
            ">Today</span>
        </div>

        {{-- Bar chart --}}
        @php
            $maxVal = $chartData->max($ckChartTab);
            $maxVal = max($maxVal, 1);
        @endphp
        <div style="
            display:flex; align-items:flex-end; gap:6px;
            height:160px; padding:0 4px;
        ">
            @foreach ($chartData as $d)
            @php
                $val = $d[$ckChartTab];
                $pct = max(round(($val / $maxVal) * 100), 4);
                $isToday = $loop->last;
            @endphp
            <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end;">
                <div style="
                    width:100%;
                    height:{{ $pct }}%;
                    background:{{ $isToday ? '#059669' : 'rgba(5,150,105,0.22)' }};
                    border-radius:6px 6px 0 0;
                    transition:height .4s ease;
                    min-height:6px;
                    position:relative;
                ">
                    @if ($isToday)
                        <div style="
                            position:absolute; top:-20px; left:50%; transform:translateX(-50%);
                            background:#18181b; color:#fff; font-size:9px; font-weight:700;
                            padding:2px 8px; border-radius:10px; white-space:nowrap;
                        ">{{ number_format($val) }}</div>
                    @endif
                </div>
                <span style="font-size:9px; font-weight:600; color:var(--ck-text-subtle); white-space:nowrap;">{{ $d['month'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Total row --}}
        <div style="display:flex; gap:24px; margin-top:16px; padding-top:14px; border-top:1px solid var(--ck-border-inner);">
            <div>
                <p style="font-size:22px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.02em;">{{ number_format($totalViews) }}</p>
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle); margin:2px 0 0;">Total Views</p>
            </div>
            <div>
                <p style="font-size:22px; font-weight:800; color:var(--ck-text-h); margin:0; letter-spacing:-.02em;">{{ number_format($totalDownloads) }}</p>
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ck-text-subtle); margin:2px 0 0;">Total Downloads</p>
            </div>
        </div>
    </div>

    {{-- ── SYSTEM INSIGHT CARD ── --}}
    <div style="
        background:linear-gradient(145deg,#7c3aed 0%,#5b21b6 50%,#4c1d95 100%);
        border-radius:20px; padding:26px;
        box-shadow:0 8px 28px rgba(124,58,237,0.35);
        display:flex; flex-direction:column; justify-content:space-between;
        position:relative; overflow:hidden;
    ">
        {{-- Background decoration --}}
        <div style="
            position:absolute; top:-40px; right:-40px;
            width:180px; height:180px; border-radius:50%;
            background:rgba(255,255,255,0.06);
        "></div>
        <div style="
            position:absolute; bottom:-30px; left:-20px;
            width:120px; height:120px; border-radius:50%;
            background:rgba(255,255,255,0.04);
        "></div>

        <div style="position:relative;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                <span style="font-size:20px; line-height:1;">💡</span>
                <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.18em; color:rgba(255,255,255,0.6);">System Insight</span>
            </div>

            <h3 style="font-size:18px; font-weight:800; color:#fff; margin:0 0 12px; line-height:1.35; letter-spacing:-.02em;">
                Demand is spiking for "Fables of the Savannah" in Nairobi.
            </h3>
            <p style="font-size:12px; color:rgba(255,255,255,0.75); line-height:1.65; margin:0 0 20px;">
                Your current distribution strategy is yielding 40% higher engagement in urban centres.
            </p>
        </div>

        <div style="position:relative; display:flex; flex-direction:column; gap:10px;">
            {{-- Mini stats --}}
            <div style="display:flex; gap:14px;">
                <div style="
                    background:rgba(255,255,255,0.12); border-radius:12px; padding:10px 14px; flex:1;
                ">
                    <p style="font-size:18px; font-weight:800; color:#fff; margin:0;">+40%</p>
                    <p style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,0.6); margin:2px 0 0;">Engagement</p>
                </div>
                <div style="
                    background:rgba(255,255,255,0.12); border-radius:12px; padding:10px 14px; flex:1;
                ">
                    <p style="font-size:18px; font-weight:800; color:#fff; margin:0;">🇰🇪</p>
                    <p style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,0.6); margin:2px 0 0;">Nairobi</p>
                </div>
            </div>
            <a href="{{ $createUrl }}" wire:navigate style="
                display:inline-flex; align-items:center; justify-content:center; gap:6px;
                padding:11px; background:rgba(255,255,255,0.15); color:#fff;
                font-size:12px; font-weight:700; border-radius:12px;
                text-decoration:none; border:1px solid rgba(255,255,255,0.2);
                transition:background .15s;
            " class="ck-insight-cta">Boost Distribution →</a>
        </div>
    </div>
</div>

</div>{{-- .ck-cms-root --}}

<style>
.ck-list-row:hover { background: var(--ck-card-hover)!important; }
.ck-cta-link:hover svg { transform:translateX(3px); transition:transform .15s; }
.ck-insight-cta:hover { background:rgba(255,255,255,0.25)!important; }
</style>

</x-filament-panels::page>
