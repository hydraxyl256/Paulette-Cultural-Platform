<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════════
     BUNDLE BUILDER  ·  Pixel-perfect  ·  Dark / Light mode
     Layout: Header → 2-col (Metadata + Asset Selection) → Footer bar
════════════════════════════════════════════════════════════════════ --}}

<style>
/* ── Light tokens ── */
:root {
    --bb-bg:           #f9fafb;
    --bb-card:         #ffffff;
    --bb-border:       rgba(228,228,231,0.8);
    --bb-border-inner: #f1f5f9;
    --bb-text-h:       #18181b;
    --bb-text-body:    #3f3f46;
    --bb-text-muted:   #71717a;
    --bb-text-subtle:  #a1a1aa;
    --bb-input-bg:     #f9fafb;
    --bb-input-border: #e4e4e7;
    --bb-surface:      rgba(248,250,252,0.95);
    --bb-shadow-sm:    0 1px 4px rgba(0,0,0,0.05);
    --bb-shadow-md:    0 4px 18px rgba(0,0,0,0.08);
    --bb-shadow-lg:    0 10px 32px rgba(0,0,0,0.12);
    --bb-asset-bg:     #f9fafb;
    --bb-asset-sel:    rgba(5,150,105,0.06);
    --bb-asset-sel-border: rgba(5,150,105,0.3);
    --bb-section-bar:  #e4e4e7;
    --bb-footer:       #ffffff;
}
.dark {
    --bb-bg:           #111118;
    --bb-card:         #1c1c27;
    --bb-border:       rgba(63,63,70,0.85);
    --bb-border-inner: rgba(39,39,42,0.9);
    --bb-text-h:       #f4f4f5;
    --bb-text-body:    #d4d4d8;
    --bb-text-muted:   #a1a1aa;
    --bb-text-subtle:  #52525b;
    --bb-input-bg:     rgba(39,39,42,0.8);
    --bb-input-border: rgba(63,63,70,0.9);
    --bb-surface:      rgba(24,24,35,0.9);
    --bb-shadow-sm:    0 1px 6px rgba(0,0,0,0.4);
    --bb-shadow-md:    0 4px 20px rgba(0,0,0,0.45);
    --bb-shadow-lg:    0 10px 36px rgba(0,0,0,0.6);
    --bb-asset-bg:     rgba(28,28,39,0.7);
    --bb-asset-sel:    rgba(52,211,153,0.08);
    --bb-asset-sel-border: rgba(52,211,153,0.35);
    --bb-section-bar:  rgba(63,63,70,0.7);
    --bb-footer:       #1c1c27;
}

/* ── Chrome override ── */
.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

/* ── Root ── */
.bb-root { font-family:'Inter','Manrope',system-ui,-apple-system,sans-serif; max-width:1320px; margin:0 auto; }

/* ── Inputs ── */
.bb-input {
    width:100%; box-sizing:border-box;
    padding:10px 14px;
    background:var(--bb-input-bg);
    border:1px solid var(--bb-input-border);
    border-radius:10px;
    font-size:13px; font-weight:500;
    color:var(--bb-text-body);
    transition:border-color .15s, box-shadow .15s;
}
.bb-input:focus { border-color:#059669; box-shadow:0 0 0 3px rgba(5,150,105,.12); outline:none; }
.bb-select { appearance:none; -webkit-appearance:none; cursor:pointer; }

/* ── Asset card animations ── */
.bb-asset { transition:transform .15s, box-shadow .15s, border-color .15s; cursor:pointer; }
.bb-asset:hover { transform:translateY(-2px); box-shadow:var(--bb-shadow-md)!important; }

/* ── Buttons ── */
.bb-ship-btn:hover { background:#047857!important; transform:translateY(-2px); box-shadow:0 8px 28px rgba(5,150,105,.45)!important; }
.bb-draft-btn:hover { color:var(--bb-text-h)!important; }

/* ── Toggle ── */
.bb-toggle { position:relative; display:inline-flex; align-items:center; cursor:pointer; }
.bb-toggle input { display:none; }
.bb-toggle-track {
    width:44px; height:24px; border-radius:99px;
    transition:background .2s;
}
.bb-toggle input:checked ~ .bb-toggle-track { background:#059669; }
.bb-toggle input:not(:checked) ~ .bb-toggle-track { background:#d1d5db; }
.dark .bb-toggle input:not(:checked) ~ .bb-toggle-track { background:#3f3f46; }
.bb-toggle-thumb {
    position:absolute; top:3px;
    width:18px; height:18px; border-radius:50%; background:#fff;
    box-shadow:0 1px 4px rgba(0,0,0,0.2);
    transition:left .2s;
}
</style>

<div class="bb-root">

{{-- ════════════════════════════════════════════════════════════════
     1 · PAGE HEADER
════════════════════════════════════════════════════════════════ --}}
<div style="margin-bottom:28px;">
    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:7px; margin-bottom:10px;">
        <div style="
            width:20px; height:20px; border-radius:5px; background:rgba(5,150,105,0.12);
            display:flex; align-items:center; justify-content:center;
        ">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        </div>
        <span style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:#059669;">Offline Systems</span>
    </div>
    <h1 style="font-size:36px; font-weight:800; color:var(--bb-text-h); margin:0 0 10px; letter-spacing:-.04em; line-height:1.05;">
        Bundle Builder
    </h1>
    <p style="font-size:14px; color:var(--bb-text-muted); margin:0; line-height:1.6; max-width:620px;">
        Curate high-performance offline content packages for schools and remote cultural hubs.<br>
        Select assets, define metadata, and ship compressed bundles.
    </p>
</div>

{{-- ════════════════════════════════════════════════════════════════
     2 · TWO-COLUMN BUILDER LAYOUT
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:380px 1fr; gap:20px; margin-bottom:140px;">

    {{-- ══════════════════════════════════════
         LEFT: Bundle Metadata + Analytics
    ══════════════════════════════════════ --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- ── Bundle Metadata card ── --}}
        <div style="
            background:var(--bb-card); border:1px solid var(--bb-border);
            border-radius:20px; padding:24px;
            box-shadow:var(--bb-shadow-md);
        ">
            {{-- Card title --}}
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:22px;">
                <div style="width:34px; height:34px; border-radius:10px; background:rgba(5,150,105,0.1); display:flex; align-items:center; justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/>
                    </svg>
                </div>
                <h2 style="font-size:16px; font-weight:700; color:var(--bb-text-h); margin:0;">Bundle Metadata</h2>
            </div>

            {{-- Bundle Title --}}
            <div style="margin-bottom:18px;">
                <label style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--bb-text-subtle); display:block; margin-bottom:7px;">Bundle Title</label>
                <input wire:model.live.debounce.500ms="ckTitle"
                       type="text" placeholder="e.g. West African Folk Tales Collection"
                       class="bb-input"
                       style="background:var(--bb-input-bg);">
            </div>

            {{-- Target Tribe + Age Range --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:18px;">
                <div>
                    <label style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--bb-text-subtle); display:block; margin-bottom:7px;">Target Tribe</label>
                    <div style="position:relative;">
                        <select wire:model.live="ckTribeId" class="bb-input bb-select" style="padding-right:32px;">
                            <option value="">Select tribe</option>
                            @foreach ($tribes as $tribe)
                                <option value="{{ $tribe->id }}">{{ $tribe->name }}</option>
                            @endforeach
                        </select>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--bb-text-subtle)" stroke-width="2.5" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none;"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>
                <div>
                    <label style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--bb-text-subtle); display:block; margin-bottom:7px;">Age Range</label>
                    <input wire:model.live.debounce.500ms="ckAgeRange" type="text" class="bb-input" value="{{ $ckAgeRange }}">
                </div>
            </div>

            {{-- Deployment Version --}}
            <div style="margin-bottom:18px;">
                <label style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--bb-text-subtle); display:block; margin-bottom:7px;">Deployment Version</label>
                <div style="position:relative;">
                    <input wire:model.live.debounce.500ms="ckVersion" type="text" class="bb-input" style="padding-right:42px;" value="{{ $ckVersion }}">
                    <button type="button"
                            onclick="navigator.clipboard.writeText(document.querySelector('[wire\\:model\\.live\\.debounce\\.500ms=ckVersion]').value)"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--bb-text-subtle); padding:2px;" title="Copy version">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    </button>
                </div>
            </div>

            {{-- Encryption Level --}}
            <div style="border-top:1px solid var(--bb-border-inner); padding-top:18px;">
                <label style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--bb-text-subtle); display:block; margin-bottom:12px;">Encryption Level</label>
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; border-radius:9px; background:rgba(5,150,105,0.1); display:flex; align-items:center; justify-content:center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <span style="font-size:13px; font-weight:700; color:var(--bb-text-body);">Military Grade<br><span style="font-weight:500; color:var(--bb-text-muted);">(AES-256)</span></span>
                    </div>
                    {{-- Custom toggle --}}
                    <label class="bb-toggle" style="cursor:pointer;">
                        <input type="checkbox" wire:model.live="ckEncryption" {{ $ckEncryption ? 'checked' : '' }}>
                        <div class="bb-toggle-track"></div>
                        <div class="bb-toggle-thumb" style="left:{{ $ckEncryption ? '23px' : '3px' }};"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ── Process Analytics card (purple gradient) ── --}}
        <div style="
            background:linear-gradient(145deg,#7c3aed 0%,#5b21b6 55%,#4c1d95 100%);
            border-radius:20px; padding:24px;
            box-shadow:0 8px 28px rgba(124,58,237,0.35);
            position:relative; overflow:hidden;
        ">
            {{-- Decorative blobs --}}
            <div style="position:absolute; top:-30px; right:-30px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.07);"></div>
            <div style="position:absolute; bottom:-20px; left:-20px; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>

            <div style="position:relative;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    <h3 style="font-size:17px; font-weight:800; color:#fff; margin:0;">Process Analytics</h3>
                </div>
                <p style="font-size:11px; color:rgba(255,255,255,0.65); margin:0 0 20px;">System impact for remote deployment</p>

                {{-- Estimated bundle size --}}
                <div style="margin-bottom:16px;">
                    <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:rgba(255,255,255,0.55); margin:0 0 6px;">Estimated Bundle Size</p>
                    <div style="display:flex; align-items:baseline; gap:8px;">
                        <span style="font-size:32px; font-weight:800; color:#fff; letter-spacing:-.04em;">{{ $bundleGB }}</span>
                        <span style="font-size:18px; font-weight:700; color:rgba(255,255,255,0.7);">{{ $bundleUnit }}</span>
                        <span style="
                            font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.1em;
                            padding:3px 10px; border-radius:20px;
                            background:rgba(255,255,255,0.2); color:#fff;
                            border:1px solid rgba(255,255,255,0.25);
                        ">Compressed</span>
                    </div>
                </div>

                {{-- Build readiness --}}
                <div style="margin-bottom:16px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:7px;">
                        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:rgba(255,255,255,0.55); margin:0;">Build Readiness</p>
                        <span style="font-size:12px; font-weight:700; color:#fff;">{{ $readinessPct }}% Complete</span>
                    </div>
                    <div style="height:7px; background:rgba(255,255,255,0.15); border-radius:99px; overflow:hidden;">
                        <div style="height:100%; width:{{ $readinessPct }}%; background:linear-gradient(90deg,#34d399,#10b981); border-radius:99px; transition:width .4s;"></div>
                    </div>
                </div>

                {{-- Stats row --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div style="background:rgba(255,255,255,0.12); border-radius:12px; padding:12px 14px;">
                        <p style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,0.55); margin:0 0 4px;">Assets Selected</p>
                        <p style="font-size:22px; font-weight:800; color:#fff; margin:0;">{{ $selectedCount }}</p>
                    </div>
                    <div style="background:rgba(255,255,255,0.12); border-radius:12px; padding:12px 14px;">
                        <p style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,0.55); margin:0 0 4px;">Bandwidth</p>
                        <p style="font-size:22px; font-weight:800; color:#fff; margin:0;">{{ $selectedCount > 0 ? rand(50,200) : '—' }} <span style="font-size:13px; font-weight:600;">Mbps</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Recent Bundles (mini history) ── --}}
        @if ($recentBundles->count() > 0)
        <div style="
            background:var(--bb-card); border:1px solid var(--bb-border);
            border-radius:20px; padding:20px;
            box-shadow:var(--bb-shadow-sm);
        ">
            <h3 style="font-size:13px; font-weight:700; color:var(--bb-text-h); margin:0 0 14px; letter-spacing:-.02em;">Recent Bundles</h3>
            @foreach ($recentBundles as $rb)
            @php
                $rsc = match($rb->status) {
                    'shipped'  => '#059669',
                    'building' => '#d97706',
                    'failed'   => '#dc2626',
                    default    => '#a1a1aa',
                };
            @endphp
            <div style="display:flex; align-items:center; justify-content:space-between; padding:9px 0; border-bottom:1px solid var(--bb-border-inner);">
                <div style="min-width:0;">
                    <p style="font-size:12px; font-weight:700; color:var(--bb-text-body); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $rb->title }}</p>
                    <p style="font-size:10px; color:var(--bb-text-subtle); margin:1px 0 0;">{{ $rb->updated_at->diffForHumans() }}</p>
                </div>
                <div style="display:inline-flex; align-items:center; gap:4px; flex-shrink:0; margin-left:8px;">
                    <div style="width:6px; height:6px; border-radius:50%; background:{{ $rsc }};"></div>
                    <span style="font-size:10px; font-weight:700; color:{{ $rsc }};">{{ ucfirst($rb->status) }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════
         RIGHT: Resource Selection
    ══════════════════════════════════════ --}}
    <div style="
        background:var(--bb-card); border:1px solid var(--bb-border);
        border-radius:20px; padding:24px;
        box-shadow:var(--bb-shadow-md);
        overflow:hidden;
    ">
        {{-- Panel header --}}
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px;">
            <div>
                <h2 style="font-size:20px; font-weight:800; color:var(--bb-text-h); margin:0 0 5px; letter-spacing:-.025em;">Resource Selection</h2>
                <p style="font-size:12px; color:var(--bb-text-muted); margin:0;">Layer assets for the cultural experience package</p>
            </div>
            {{-- All / Selected tabs --}}
            <div style="display:flex; gap:6px;">
                <button type="button" wire:click="$set('ckAssetTab','all')"
                        style="
                            padding:7px 16px; border-radius:20px; font-size:12px; font-weight:700; cursor:pointer;
                            border:1.5px solid {{ $ckAssetTab === 'all' ? '#059669' : 'var(--bb-border)' }};
                            background:{{ $ckAssetTab === 'all' ? 'rgba(5,150,105,0.08)' : 'transparent' }};
                            color:{{ $ckAssetTab === 'all' ? '#059669' : 'var(--bb-text-muted)' }};
                            transition:all .15s;
                        ">All Assets</button>
                <button type="button" wire:click="$set('ckAssetTab','selected')"
                        style="
                            padding:7px 16px; border-radius:20px; font-size:12px; font-weight:700; cursor:pointer;
                            border:1.5px solid {{ $ckAssetTab === 'selected' ? '#059669' : 'var(--bb-border)' }};
                            background:{{ $ckAssetTab === 'selected' ? 'rgba(5,150,105,0.08)' : 'transparent' }};
                            color:{{ $ckAssetTab === 'selected' ? '#059669' : 'var(--bb-text-muted)' }};
                            transition:all .15s;
                        ">Selected ({{ $selectedCount }})</button>
            </div>
        </div>

        {{-- ── GRAPHIC STORIES & COMICS ── --}}
        @php
            $visibleComics = $ckAssetTab === 'selected'
                ? $comics->filter(fn($c) => in_array($c['key'], $ckSelectedIds))
                : $comics;
        @endphp
        @if ($visibleComics->isNotEmpty())
        <div style="margin-bottom:20px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <div style="width:4px; height:16px; border-radius:2px; background:#ea580c;"></div>
                <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:var(--bb-text-subtle);">Graphic Stories &amp; Comics</span>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                @foreach ($visibleComics->take(6) as $asset)
                @php $isSelected = in_array($asset['key'], $ckSelectedIds); @endphp
                <div class="bb-asset"
                     wire:click="ckToggleAsset('{{ $asset['key'] }}')"
                     style="
                         display:flex; align-items:center; gap:12px;
                         padding:12px 14px;
                         border-radius:16px;
                         border:1.5px solid {{ $isSelected ? 'var(--bb-asset-sel-border)' : 'var(--bb-border)' }};
                         background:{{ $isSelected ? 'var(--bb-asset-sel)' : 'var(--bb-asset-bg)' }};
                         box-shadow:var(--bb-shadow-sm);
                         {{ $asset['status'] === 'published' ? '' : 'opacity:.65;' }}
                     ">
                    {{-- Icon / thumbnail --}}
                    <div style="
                        width:44px; height:44px; border-radius:10px; flex-shrink:0;
                        background:{{ $isSelected ? 'rgba(5,150,105,0.15)' : 'var(--bb-border-inner)' }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:20px; overflow:hidden;
                    ">
                        {{ $asset['icon'] }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:13px; font-weight:700; color:var(--bb-text-h); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $asset['name'] }}</p>
                        <p style="font-size:10px; color:var(--bb-text-muted); margin:2px 0 0;">{{ $asset['size'] }} • {{ $asset['quality'] }}</p>
                    </div>
                    {{-- Checkbox circle --}}
                    <div style="
                        width:22px; height:22px; border-radius:50%; flex-shrink:0;
                        background:{{ $isSelected ? '#059669' : 'transparent' }};
                        border:2px solid {{ $isSelected ? '#059669' : 'var(--bb-border)' }};
                        display:flex; align-items:center; justify-content:center;
                        transition:all .15s;
                    ">
                        @if ($isSelected)
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── FOLK SONGS & AUDIO ── --}}
        @php
            $visibleAudio = $ckAssetTab === 'selected'
                ? $audio->filter(fn($a) => in_array($a['key'], $ckSelectedIds))
                : $audio;
        @endphp
        @if ($visibleAudio->isNotEmpty())
        <div style="margin-bottom:20px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <div style="width:4px; height:16px; border-radius:2px; background:#d97706;"></div>
                <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:var(--bb-text-subtle);">Folk Songs &amp; Audio</span>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                @foreach ($visibleAudio->take(6) as $asset)
                @php $isSelected = in_array($asset['key'], $ckSelectedIds); @endphp
                <div class="bb-asset"
                     wire:click="ckToggleAsset('{{ $asset['key'] }}')"
                     style="
                         display:flex; align-items:center; gap:12px;
                         padding:12px 14px;
                         border-radius:16px;
                         border:1.5px solid {{ $isSelected ? 'var(--bb-asset-sel-border)' : 'var(--bb-border)' }};
                         background:{{ $isSelected ? 'var(--bb-asset-sel)' : 'var(--bb-asset-bg)' }};
                         box-shadow:var(--bb-shadow-sm);
                         {{ $asset['status'] === 'live' ? '' : 'opacity:.65;' }}
                     ">
                    <div style="
                        width:44px; height:44px; border-radius:50%; flex-shrink:0;
                        background:{{ $isSelected ? 'rgba(5,150,105,0.15)' : 'rgba(5,150,105,0.08)' }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:18px;
                        border:1px solid {{ $isSelected ? 'rgba(5,150,105,0.3)' : 'rgba(5,150,105,0.15)' }};
                    ">
                        🎵
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:13px; font-weight:700; color:var(--bb-text-h); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $asset['name'] }}</p>
                        <p style="font-size:10px; color:var(--bb-text-muted); margin:2px 0 0;">{{ $asset['size'] }} • {{ $asset['quality'] }}</p>
                    </div>
                    <div style="
                        width:22px; height:22px; border-radius:50%; flex-shrink:0;
                        background:{{ $isSelected ? '#059669' : 'transparent' }};
                        border:2px solid {{ $isSelected ? '#059669' : 'var(--bb-border)' }};
                        display:flex; align-items:center; justify-content:center;
                        transition:all .15s;
                    ">
                        @if ($isSelected)
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── INTERACTIVE FLASHCARDS ── --}}
        @php
            $visibleDecks = $ckAssetTab === 'selected'
                ? $decks->filter(fn($d) => in_array($d['key'], $ckSelectedIds))
                : $decks;
        @endphp
        @if ($visibleDecks->isNotEmpty())
        <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <div style="width:4px; height:16px; border-radius:2px; background:#7c3aed;"></div>
                <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:var(--bb-text-subtle);">Interactive Flashcards</span>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                @foreach ($visibleDecks->take(6) as $asset)
                @php $isSelected = in_array($asset['key'], $ckSelectedIds); @endphp
                <div class="bb-asset"
                     wire:click="ckToggleAsset('{{ $asset['key'] }}')"
                     style="
                         display:flex; align-items:center; gap:12px;
                         padding:12px 14px;
                         border-radius:16px;
                         border:1.5px solid {{ $isSelected ? 'var(--bb-asset-sel-border)' : 'var(--bb-border)' }};
                         background:{{ $isSelected ? 'var(--bb-asset-sel)' : 'var(--bb-asset-bg)' }};
                         box-shadow:var(--bb-shadow-sm);
                         {{ $asset['status'] === 'live' ? '' : 'opacity:.65;' }}
                     ">
                    <div style="
                        width:44px; height:44px; border-radius:10px; flex-shrink:0;
                        background:{{ $isSelected ? 'rgba(5,150,105,0.15)' : 'rgba(124,58,237,0.08)' }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:18px;
                        border:1px solid {{ $isSelected ? 'rgba(5,150,105,0.25)' : 'rgba(124,58,237,0.15)' }};
                    ">
                        🃏
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:13px; font-weight:700; color:var(--bb-text-h); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $asset['name'] }}</p>
                        <p style="font-size:10px; color:var(--bb-text-muted); margin:2px 0 0;">{{ $asset['size'] }} • {{ $asset['quality'] }}</p>
                    </div>
                    <div style="
                        width:22px; height:22px; border-radius:50%; flex-shrink:0;
                        background:{{ $isSelected ? '#059669' : 'transparent' }};
                        border:2px solid {{ $isSelected ? '#059669' : 'var(--bb-border)' }};
                        display:flex; align-items:center; justify-content:center;
                        transition:all .15s;
                    ">
                        @if ($isSelected)
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if ($comics->isEmpty() && $audio->isEmpty() && $decks->isEmpty())
        <div style="text-align:center; padding:60px 20px;">
            <p style="font-size:40px; margin:0 0 14px;">📦</p>
            <h3 style="font-size:16px; font-weight:700; color:var(--bb-text-muted); margin:0 0 6px;">No assets available</h3>
            <p style="font-size:13px; color:var(--bb-text-subtle); margin:0;">Add comics, audio tracks, or flashcard decks first.</p>
        </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     3 · STICKY FOOTER BAR
════════════════════════════════════════════════════════════════ --}}
<div style="
    position:fixed; bottom:0; left:230px; right:0; z-index:100;
    background:var(--bb-footer);
    border-top:1px solid var(--bb-border);
    box-shadow:0 -4px 24px rgba(0,0,0,0.08);
    padding:16px 40px;
    display:flex; align-items:center; gap:32px;
">
    {{-- Bundle size --}}
    <div>
        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:var(--bb-text-subtle); margin:0 0 4px;">Estimated Bundle Size</p>
        <div style="display:flex; align-items:baseline; gap:7px;">
            <span style="font-size:22px; font-weight:800; color:var(--bb-text-h); letter-spacing:-.03em;">{{ $bundleGB }}</span>
            <span style="font-size:14px; font-weight:700; color:var(--bb-text-muted);">{{ $bundleUnit }}</span>
            <span style="
                font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
                padding:2px 9px; border-radius:20px;
                background:rgba(5,150,105,0.1); color:#059669;
                border:1px solid rgba(5,150,105,0.2);
            ">Compressed</span>
        </div>
    </div>

    {{-- Build readiness --}}
    <div style="flex:1; max-width:360px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
            <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:var(--bb-text-subtle); margin:0;">Build Readiness</p>
            <span style="font-size:12px; font-weight:700; color:var(--bb-text-body);">{{ $readinessPct }}% Complete</span>
        </div>
        <div style="height:6px; background:var(--bb-border-inner); border-radius:99px; overflow:hidden;">
            <div style="height:100%; width:{{ $readinessPct }}%; background:linear-gradient(90deg,#059669,#34d399); border-radius:99px; transition:width .4s;"></div>
        </div>
    </div>

    {{-- Spacer --}}
    <div style="flex:1;"></div>

    {{-- Save Draft --}}
    <button type="button" wire:click="ckSaveDraft" class="bb-draft-btn"
            wire:loading.attr="disabled" wire:target="ckSaveDraft"
            style="
                font-size:14px; font-weight:700;
                color:var(--bb-text-muted); background:none; border:none;
                cursor:pointer; padding:12px 20px;
                transition:color .15s;
            ">
        <span wire:loading.remove wire:target="ckSaveDraft">Save Draft</span>
        <span wire:loading wire:target="ckSaveDraft">Saving...</span>
    </button>

    {{-- Build & Ship --}}
    <button type="button" wire:click="ckBuildAndShip" class="bb-ship-btn"
            wire:loading.attr="disabled" wire:target="ckBuildAndShip"
            wire:confirm="Build and ship '{{ $ckTitle ?: 'this bundle' }}'? This will package all selected assets for deployment."
            style="
                display:inline-flex; align-items:center; gap:10px;
                padding:13px 28px;
                background:#052e16; color:#fff;
                font-size:14px; font-weight:700;
                border-radius:40px; border:none; cursor:pointer;
                box-shadow:0 4px 18px rgba(5,150,105,.3);
                transition:all .2s;
            ">
        <span wire:loading.remove wire:target="ckBuildAndShip">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="margin-right:2px;">
                <path d="M9.663 17h4.673M12 3v1m6.364 1.636-.707.707M21 12h-1M4 12H3m3.343-5.657-.707-.707m2.828 9.9a5 5 0 1 1 7.072 0l-.548.547A3.374 3.374 0 0 0 14 18.469V19a2 2 0 1 1-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            Build &amp; Ship Bundle
        </span>
        <span wire:loading wire:target="ckBuildAndShip">Building...</span>
    </button>
</div>

</div>{{-- .bb-root --}}

<style>
/* ── Live toggle thumb position via JS ── */
</style>

<script>
// Keep toggle thumb position in sync with Livewire state
document.addEventListener('livewire:updated', () => {
    document.querySelectorAll('.bb-toggle input[type=checkbox]').forEach(cb => {
        const thumb = cb.parentElement.querySelector('.bb-toggle-thumb');
        if (thumb) thumb.style.left = cb.checked ? '23px' : '3px';
    });
});
</script>

</x-filament-panels::page>
