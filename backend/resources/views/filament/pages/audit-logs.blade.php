<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════
     AUDIT LOGS — Pixel-perfect · Dark / Light · Livewire
     Layout: Filters → Table → Slide-over Detail Panel
════════════════════════════════════════════════════════════════ --}}

<style>
:root {
    --al-card:        #ffffff;
    --al-border:      rgba(228,228,231,0.85);
    --al-border-in:   #f0f0f2;
    --al-text-h:      #09090b;
    --al-text-body:   #3f3f46;
    --al-text-muted:  #71717a;
    --al-text-subtle: #a1a1aa;
    --al-surface:     #f8fafc;
    --al-shadow:      0 1px 4px rgba(0,0,0,0.06);
    --al-shadow-md:   0 4px 20px rgba(0,0,0,0.09);
    --al-code-bg:     #181c2d;
    --al-code-text:   #a8d9b0;
    --al-overlay:     rgba(0,0,0,0.35);
}
.dark {
    --al-card:        #1c1c27;
    --al-border:      rgba(63,63,70,0.8);
    --al-border-in:   rgba(39,39,42,0.9);
    --al-text-h:      #f4f4f5;
    --al-text-body:   #d4d4d8;
    --al-text-muted:  #a1a1aa;
    --al-text-subtle: #52525b;
    --al-surface:     rgba(24,24,35,0.9);
    --al-shadow:      0 1px 6px rgba(0,0,0,0.4);
    --al-shadow-md:   0 4px 22px rgba(0,0,0,0.5);
    --al-code-bg:     #0d1117;
    --al-code-text:   #7ee787;
    --al-overlay:     rgba(0,0,0,0.6);
}

.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }
.al-root { font-family:'Inter','Manrope',system-ui,sans-serif; max-width:1320px; margin:0 auto; }

/* Transitions */
.al-row { transition:background .12s; cursor:pointer; }
.al-row:hover { background:var(--al-surface)!important; }
.al-filter-card:hover { border-color:#059669!important; }
.al-btn-primary:hover { background:#047857!important; }
.al-search:focus { border-color:#059669!important; box-shadow:0 0 0 3px rgba(5,150,105,.12)!important; outline:none; }

/* Slide-over overlay */
.al-overlay {
    position:fixed; inset:0; z-index:400;
    background:var(--al-overlay);
    backdrop-filter:blur(2px);
    animation:al-fade-in .15s ease;
}
.al-panel {
    position:fixed; top:0; right:0; bottom:0; width:380px; z-index:401;
    background:var(--al-card); border-left:1px solid var(--al-border);
    box-shadow:-8px 0 40px rgba(0,0,0,0.18);
    overflow-y:auto; animation:al-slide-in .22s ease;
}
@keyframes al-fade-in { from{opacity:0} to{opacity:1} }
@keyframes al-slide-in { from{transform:translateX(100%)} to{transform:translateX(0)} }

/* JSON syntax colours */
.al-json-key   { color:#79c0ff; }
.al-json-str   { color:#a8d9b0; }
.al-json-num   { color:#ffa657; }
.al-json-bool  { color:#d2a8ff; }

/* Avatar */
.al-avatar {
    width:32px; height:32px; border-radius:50%;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:12px; font-weight:700; color:#fff; flex-shrink:0;
}

/* Map placeholder */
.al-map {
    border-radius:12px; height:130px;
    background:linear-gradient(160deg,#1a2035 0%,#0d1a2e 100%);
    position:relative; overflow:hidden;
}
</style>

<div class="al-root">

{{-- ════ BREADCRUMB + HEADER ════ --}}
<div style="margin-bottom:6px;">
    <div style="display:flex; align-items:center; gap:6px; margin-bottom:14px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#059669;">SYSTEM</span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--al-text-subtle)" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--al-text-subtle);">AUDIT LOGS</span>
    </div>
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 style="font-size:34px; font-weight:800; color:var(--al-text-h); margin:0 0 8px; letter-spacing:-.04em;">Audit Logs</h1>
            <p style="font-size:13px; color:var(--al-text-muted); margin:0; max-width:480px; line-height:1.6;">
                Real-time monitoring of all administrative actions across the ecosystem.
            </p>
        </div>
        {{-- Search --}}
        <div style="position:relative; flex-shrink:0; min-width:280px; margin-top:4px;">
            <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%);" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--al-text-muted)" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" wire:model.live.debounce.400ms="ckSearch" placeholder="Search system events, user IDs, or IPs..."
                class="al-search" style="
                    width:100%; padding:10px 16px 10px 36px;
                    background:var(--al-card); border:1px solid var(--al-border);
                    border-radius:10px; font-size:13px; color:var(--al-text-body);
                    box-sizing:border-box; transition:all .2s;
                "/>
        </div>
    </div>
</div>

{{-- ════ FILTER CHIPS ════ --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:22px;">

    {{-- Date Range --}}
    <div class="al-filter-card" style="
        background:var(--al-card); border:1px solid var(--al-border);
        border-radius:14px; padding:14px 18px;
        box-shadow:var(--al-shadow); cursor:pointer; transition:border-color .15s;
    ">
        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--al-text-subtle); margin:0 0 6px;">Date Range</p>
        <div style="display:flex; align-items:center; gap:8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--al-text-muted)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <div style="display:flex; gap:6px; align-items:center;">
                <input type="date" wire:model.live="ckDateFrom" style="font-size:12px; font-weight:600; color:var(--al-text-body); background:transparent; border:none; outline:none; cursor:pointer;" />
                <span style="font-size:12px; color:var(--al-text-subtle);">–</span>
                <input type="date" wire:model.live="ckDateTo" style="font-size:12px; font-weight:600; color:var(--al-text-body); background:transparent; border:none; outline:none; cursor:pointer;" />
            </div>
        </div>
    </div>

    {{-- User Access --}}
    <div class="al-filter-card" style="
        background:var(--al-card); border:1px solid var(--al-border);
        border-radius:14px; padding:14px 18px;
        box-shadow:var(--al-shadow); transition:border-color .15s;
    ">
        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--al-text-subtle); margin:0 0 6px;">User Access</p>
        <div style="display:flex; align-items:center; gap:8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--al-text-muted)" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <select wire:model.live="ckUserFilter" style="font-size:12px; font-weight:600; color:var(--al-text-body); background:transparent; border:none; outline:none; cursor:pointer; flex:1;">
                <option value="">All Administrators</option>
                @foreach ($users as $u)
                    <option value="{{ $u }}">{{ $u }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Action Type --}}
    <div class="al-filter-card" style="
        background:var(--al-card); border:1px solid var(--al-border);
        border-radius:14px; padding:14px 18px;
        box-shadow:var(--al-shadow); transition:border-color .15s;
    ">
        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--al-text-subtle); margin:0 0 6px;">Action Type</p>
        <div style="display:flex; align-items:center; gap:8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--al-text-muted)" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <select wire:model.live="ckActionFilter" style="font-size:12px; font-weight:600; color:var(--al-text-body); background:transparent; border:none; outline:none; cursor:pointer; flex:1;">
                <option value="">All Actions</option>
                @foreach ($actions as $act)
                    <option value="{{ $act }}">{{ ucfirst(str_replace('_',' ',$act)) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ════ EVENTS TABLE ════ --}}
<div style="background:var(--al-card); border:1px solid var(--al-border); border-radius:18px; padding:0; box-shadow:var(--al-shadow); overflow:hidden; margin-bottom:18px;">

    {{-- Column headers --}}
    <div style="display:grid; grid-template-columns:160px 1fr 1fr 140px; gap:0; padding:13px 22px; border-bottom:1px solid var(--al-border-in);">
        @foreach (['TIMESTAMP','USER','ACTION','RESOURCE'] as $col)
        <span style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--al-text-subtle);">{{ $col }}</span>
        @endforeach
    </div>

    {{-- Rows --}}
    @if ($useDemo)
        @foreach ($demoEvents as $de)
        <div class="al-row" style="
            display:grid; grid-template-columns:160px 1fr 1fr 140px; gap:0;
            padding:16px 22px; border-bottom:1px solid var(--al-border-in);
            background:{{ $de['row_bg'] }};
            {{ $loop->last ? 'border-bottom:none;' : '' }}
        " onclick="window.alert('Select a real event to view details')">
            {{-- Timestamp --}}
            <div>
                <p style="font-size:12px; font-weight:600; color:var(--al-text-body); margin:0; white-space:pre-line;">{{ explode(' / ', $de['ts'])[0] }}</p>
                <p style="font-size:11px; color:var(--al-text-subtle); margin:2px 0 0; font-family:monospace;">{{ explode(' / ', $de['ts'])[1] }}</p>
            </div>
            {{-- User --}}
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="al-avatar" style="background:{{ in_array($de['icon'],['F','A','L']) ? '#059669' : '#374151' }};">{{ $de['icon'] }}</div>
                <span style="font-size:13px; font-weight:600; color:var(--al-text-body);">{{ $de['user'] }}</span>
            </div>
            {{-- Action --}}
            <p style="font-size:13px; font-weight:600; color:{{ $de['action_color'] }}; margin:0; display:flex; align-items:center;">{{ $de['action_label'] }}</p>
            {{-- Resource --}}
            <p style="font-size:12px; color:var(--al-text-muted); margin:0; font-family:monospace;">{{ $de['resource'] }} #{{ $de['resource_id'] }}</p>
        </div>
        @endforeach
    @else
        @forelse ($events as $event)
        @php
            $meta     = $actionMeta($event->action);
            $userName = $event->user?->name ?? 'System';
            $initial  = mb_strtoupper(mb_substr($userName, 0, 1));
            $colors   = ['#059669','#3b82f6','#7c3aed','#d97706','#dc2626','#06b6d4'];
            $clr      = $colors[$event->id % count($colors)];
            $resource = $event->model_type ? class_basename($event->model_type) . ' #' . $event->model_id : '—';
        @endphp
        <div class="al-row" style="
            display:grid; grid-template-columns:160px 1fr 1fr 140px; gap:0;
            padding:16px 22px; border-bottom:1px solid var(--al-border-in);
            background:{{ $meta['row_bg'] }};
            {{ $loop->last ? 'border-bottom:none;' : '' }}
        " wire:click="ckOpenDetail({{ $event->id }})">
            {{-- Timestamp --}}
            <div>
                <p style="font-size:12px; font-weight:600; color:var(--al-text-body); margin:0;">{{ $event->created_at?->format('M d, Y') }}</p>
                <p style="font-size:11px; color:var(--al-text-subtle); margin:2px 0 0; font-family:monospace;">{{ $event->created_at?->format('H:i:s.v') }}</p>
            </div>
            {{-- User --}}
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="al-avatar" style="background:{{ $clr }};">{{ $initial }}</div>
                <span style="font-size:13px; font-weight:600; color:var(--al-text-body);">{{ $userName }}</span>
            </div>
            {{-- Action --}}
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:13px; font-weight:600; color:{{ $meta['color'] }};">
                    {{ ucfirst(str_replace('_', ' ', $event->action)) }}
                </span>
            </div>
            {{-- Resource --}}
            <p style="font-size:12px; color:var(--al-text-muted); margin:0; font-family:monospace; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $resource }}</p>
        </div>
        @empty
        <div style="padding:48px; text-align:center;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--al-text-subtle)" stroke-width="1.2" style="margin:0 auto 12px; display:block;">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <p style="font-size:15px; font-weight:700; color:var(--al-text-muted); margin:0;">No events match your filters</p>
        </div>
        @endforelse
    @endif

    {{-- Pagination footer --}}
    <div style="padding:14px 22px; border-top:1px solid var(--al-border-in); display:flex; align-items:center; justify-content:space-between;">
        <p style="font-size:13px; color:var(--al-text-muted); margin:0;">
            Showing <strong style="color:var(--al-text-body);">{{ ($ckPage-1)*$ckPerPage+1 }}-{{ min($ckPage*$ckPerPage,$total) }}</strong>
            of <strong style="color:var(--al-text-body);">{{ number_format($total) }}</strong> events
        </p>
        <div style="display:flex; gap:6px;">
            @if ($ckPage > 1)
            <button type="button" wire:click="$set('ckPage', {{ $ckPage - 1 }})" style="
                padding:6px 14px; border:1px solid var(--al-border); border-radius:8px;
                background:var(--al-surface); font-size:12px; font-weight:600;
                color:var(--al-text-body); cursor:pointer;
            ">← Prev</button>
            @endif
            @if ($ckPage < $pages)
            <button type="button" wire:click="$set('ckPage', {{ $ckPage + 1 }})" style="
                padding:6px 14px; border:1px solid var(--al-border); border-radius:8px;
                background:var(--al-surface); font-size:12px; font-weight:600;
                color:var(--al-text-body); cursor:pointer;
            ">Next →</button>
            @endif
        </div>
    </div>
</div>

{{-- ════ SLIDE-OVER DETAIL PANEL ════ --}}
@if ($ckShowDetail && $selected)

{{-- Overlay --}}
<div class="al-overlay" wire:click="ckCloseDetail"></div>

{{-- Panel --}}
<div class="al-panel">
    {{-- Panel header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; padding:22px 22px 18px; border-bottom:1px solid var(--al-border-in);">
        <h2 style="font-size:20px; font-weight:800; color:var(--al-text-h); margin:0;">Event Details</h2>
        <button type="button" wire:click="ckCloseDetail" style="
            width:32px; height:32px; border-radius:8px; border:1px solid var(--al-border);
            background:var(--al-surface); display:flex; align-items:center; justify-content:center; cursor:pointer;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--al-text-muted)" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <div style="padding:20px 22px; display:flex; flex-direction:column; gap:18px;">

        {{-- Event type card --}}
        @if ($selectedMeta)
        <div style="display:flex; align-items:flex-start; gap:14px; padding:16px; background:{{ $selectedMeta['bg'] }}; border-radius:14px; border:1px solid {{ $selectedMeta['color'] }}22;">
            <div style="width:40px; height:40px; border-radius:10px; background:{{ $selectedMeta['color'] }}22; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $selectedMeta['color'] }}" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div>
                <p style="font-size:16px; font-weight:800; color:var(--al-text-h); margin:0 0 4px;">{{ $selectedMeta['label'] }}</p>
                @if ($selectedMeta['severity'])
                <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:{{ $selectedMeta['color'] }};">{{ $selectedMeta['severity'] }}</span>
                @endif
            </div>
        </div>
        @endif

        {{-- Actor + Target --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
            <div>
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--al-text-subtle); margin:0 0 5px;">Actor</p>
                <p style="font-size:14px; font-weight:700; color:var(--al-text-h); margin:0;">{{ $selected->user?->name ?? 'System' }}</p>
            </div>
            <div>
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--al-text-subtle); margin:0 0 5px;">Target</p>
                <p style="font-size:14px; font-weight:700; color:var(--al-text-h); margin:0;">
                    {{ $selected->model_type ? class_basename($selected->model_type) . ' #' . $selected->model_id : '—' }}
                </p>
            </div>
        </div>

        {{-- Metadata Payload --}}
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--al-text-subtle); margin:0;">Metadata Payload</p>
                <button type="button" onclick="navigator.clipboard.writeText(this.dataset.json)" data-json="{{ htmlspecialchars($payload ?? '{}') }}" style="
                    display:inline-flex; align-items:center; gap:5px;
                    font-size:11px; font-weight:700; color:#059669;
                    background:rgba(5,150,105,0.08); border:none; border-radius:6px;
                    padding:4px 10px; cursor:pointer;
                ">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    Copy JSON
                </button>
            </div>
            <div style="background:var(--al-code-bg); border-radius:12px; padding:14px; overflow:auto; max-height:230px;">
                <pre style="margin:0; font-size:11px; font-family:'Fira Code','JetBrains Mono',monospace; color:var(--al-code-text); line-height:1.6; white-space:pre-wrap; word-break:break-all;">{{ $payload ?? '{}' }}</pre>
            </div>
        </div>

        {{-- Map section --}}
        <div>
            <div class="al-map">
                {{-- SVG map grid --}}
                <svg width="100%" height="100%" viewBox="0 0 380 130" preserveAspectRatio="xMidYMid slice" style="opacity:.6;">
                    <defs>
                        <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="380" height="130" fill="url(#grid)"/>
                    {{-- City road lines --}}
                    <path d="M50 65 Q100 40 150 65 Q200 90 250 65 Q300 40 340 65" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="1.5"/>
                    <path d="M80 10 Q120 50 100 90" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    <path d="M200 5 Q220 65 200 125" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    <path d="M290 10 Q310 50 290 110" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    {{-- Location pin --}}
                    <circle cx="190" cy="62" r="6" fill="#059669" opacity=".9"/>
                    <circle cx="190" cy="62" r="10" fill="rgba(5,150,105,0.25)" stroke="#059669" stroke-width="1"/>
                </svg>
                {{-- Overlay label --}}
                <div style="position:absolute; bottom:10px; left:14px; display:flex; align-items:center; gap:6px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.85);">Request Origin:
                        {{ $selected->ip_address ? 'Nairobi, Kenya' : 'Unknown' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex; flex-direction:column; gap:10px; margin-top:4px;">
            <button type="button" wire:click="ckValidateChain" class="al-btn-primary" style="
                width:100%; padding:14px; border:none; border-radius:12px; cursor:pointer;
                background:linear-gradient(135deg,#7c3aed,#5b21b6);
                color:#fff; font-size:14px; font-weight:700;
                display:flex; align-items:center; justify-content:center; gap:8px;
                box-shadow:0 4px 18px rgba(124,58,237,0.35); transition:all .15s;
            ">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
                Validate Audit Chain
            </button>

            <button type="button" wire:click="ckRevokeSession" style="
                width:100%; padding:13px; border:1.5px solid #dc2626; border-radius:12px; cursor:pointer;
                background:transparent; color:#dc2626;
                font-size:14px; font-weight:700;
                display:flex; align-items:center; justify-content:center; gap:8px;
                transition:all .15s;
            "
            onmouseover="this.style.background='rgba(220,38,38,0.06)'"
            onmouseout="this.style.background='transparent'">
                Revoke Admin Session
            </button>
        </div>

    </div>
</div>

@endif

</div>{{-- .al-root --}}
</x-filament-panels::page>
