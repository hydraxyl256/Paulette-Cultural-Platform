<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════
     THEME ENGINE — Pixel-perfect · Dark / Light · Livewire
════════════════════════════════════════════════════════════ --}}

<style>
:root {
    --te-card:        #ffffff;
    --te-border:      rgba(228,228,231,0.85);
    --te-text-h:      #09090b;
    --te-text-body:   #3f3f46;
    --te-text-subtle: #a1a1aa;
    --te-surface:     #fafafa;
    --te-shadow:      0 8px 30px rgba(0,0,0,0.04);
}
.dark {
    --te-card:        #18181b;
    --te-border:      rgba(63,63,70,0.8);
    --te-text-h:      #f4f4f5;
    --te-text-body:   #d4d4d8;
    --te-text-subtle: #52525b;
    --te-surface:     #09090b;
    --te-shadow:      0 8px 30px rgba(0,0,0,0.5);
}

.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }
.te-root { font-family:'Inter','Manrope',system-ui,sans-serif; max-width:1300px; margin:0 auto; padding-bottom:40px; }

/* Custom Sliders */
.te-slider { -webkit-appearance:none; width:100%; height:4px; background:var(--te-border); border-radius:4px; outline:none; transition:background .15s; }
.te-slider::-webkit-slider-thumb { -webkit-appearance:none; appearance:none; width:14px; height:14px; border-radius:50%; cursor:pointer; }
.te-slider.pri::-webkit-slider-thumb { background:{{ $priHex }}; }
.te-slider.sec::-webkit-slider-thumb { background:{{ $secHex }}; }
.te-slider.acc::-webkit-slider-thumb { background:{{ $accHex }}; }
.te-slider.rad::-webkit-slider-thumb { background:#10b981; }

.te-btn-primary { background:{{ $priHex }}; color:#fff; transition:transform 0.15s, box-shadow 0.15s; }
.te-btn-primary:hover { transform:translateY(-1px); box-shadow:0 10px 25px rgba(0,0,0,0.15); }

.te-btn-secondary { background:var(--te-card); border:1px solid var(--te-border); color:var(--te-text-h); transition:all 0.1s; }
.te-btn-secondary:hover { background:var(--te-surface); }

/* DNA Cards */
.te-dna-section h4 { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:var(--te-text-subtle); margin-top:30px; margin-bottom:14px; }
.te-dna-card { background:var(--te-card); border-radius:{{ $cornerRadius }}px; padding:24px; box-shadow:var(--te-shadow); border:1px solid rgba(0,0,0,0.02); transition:border-radius 0.3s ease; }
.dark .te-dna-card { border-color:var(--te-border); }

/* Radios */
.te-radio-group { display:flex; gap:10px; background:var(--te-surface); padding:4px; border-radius:30px; border:1px solid var(--te-border); }
.te-radio-btn { padding:6px 16px; border-radius:20px; font-size:11px; font-weight:700; cursor:pointer; color:var(--te-text-subtle); border:none; background:transparent; transition:all 0.2s; flex:1; text-align:center; }
.te-radio-btn.active { background:var(--te-card); color:var(--te-text-h); box-shadow:0 2px 8px rgba(0,0,0,0.06); }
</style>

<div class="te-root">

    {{-- Breadcrumb & Top Bar --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:30px;">
        <div style="font-size:13px; font-weight:600; color:var(--te-text-h);">
            <span style="color:var(--te-text-subtle);">System / </span> Theme Engine
        </div>
        <div style="display:flex; align-items:center; gap:20px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--te-text-subtle)" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <div style="position:relative;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--te-text-subtle)" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <div style="position:absolute; top:-2px; right:-2px; width:8px; height:8px; background:#ef4444; border-radius:50%; border:2px solid var(--te-surface);"></div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; border-left:1px solid var(--te-border); padding-left:20px;">
                <div style="text-align:right;">
                    <p style="font-size:12px; font-weight:700; margin:0; color:var(--te-text-h);">{{ auth()->user()?->name ?? 'Admin User' }}</p>
                    <p style="font-size:10px; color:var(--te-text-subtle); margin:0;">Super Admin</p>
                </div>
                <div style="width:32px; height:32px; border-radius:50%; background:var(--te-text-h); display:flex; align-items:center; justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--te-card)" stroke="none"><circle cx="12" cy="8" r="4"/><path d="M4 22c0-4 4-7 8-7s8 3 8 7"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:30px;">
        <div>
            <h1 style="font-size:32px; font-weight:800; color:var(--te-text-h); margin:0 0 8px; letter-spacing:-.03em;">Live Identity Preview</h1>
            <p style="font-size:14px; color:var(--te-text-subtle); margin:0;">Simulating visual hierarchy across core system components.</p>
        </div>
        <button wire:click="ckSyncActive" style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:30px; background:var(--te-card); border:1px solid var(--te-border); color:var(--te-text-h); font-size:11px; font-weight:800; cursor:pointer; box-shadow:var(--te-shadow);">
            <div style="width:6px; height:6px; background:#10b981; border-radius:50%;"></div>
            Active Syncing
        </button>
    </div>

    {{-- Hero Preview Canvas --}}
    <div style="background:var(--te-surface); border-radius:{{ $cornerRadius }}px; padding:60px 50px; position:relative; overflow:hidden; transition:border-radius 0.3s ease; margin-bottom:0;">
        {{-- Hero Blob (subtle background gradient) --}}
        <div style="position:absolute; top: -20%; right: -10%; width: 60%; height: 140%; background: radial-gradient(circle, {{ $priHex }}10 0%, transparent 60%); z-index: 0; pointer-events: none;"></div>

        <div style="display:flex; justify-content:space-between; gap:40px; position:relative; z-index:1;">
            
            {{-- Left: Typographical Hero --}}
            <div style="flex:1; max-width:580px;">
                <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:{{ $priHex }}; background:{{ $priHex }}15; padding:6px 14px; border-radius:20px; display:inline-block; margin-bottom:24px;">HERO LAYOUT PREVIEW</span>
                <h2 style="font-size:62px; font-weight:800; line-height:1.05; color:var(--te-text-h); margin:0 0 24px; letter-spacing:-.04em;">
                    Cultivating <br>
                    <span style="color:{{ $priHex }}; font-style:italic;">digital</span> <br>
                    storytellers.
                </h2>
                <p style="font-size:18px; line-height:1.6; color:var(--te-text-body); margin:0 0 40px; max-width:480px;">
                    Experience how your brand colors translate to real world interaction patterns and high-density content layouts.
                </p>
                <div style="display:flex; gap:16px;">
                    <button class="te-btn-primary" style="padding:14px 28px; border:none; border-radius:{{ min($cornerRadius, 14) }}px; font-size:14px; font-weight:700; cursor:pointer;">Primary Action</button>
                    <button class="te-btn-secondary" style="padding:14px 28px; border-radius:{{ min($cornerRadius, 14) }}px; font-size:14px; font-weight:700; cursor:pointer;">Secondary Button</button>
                </div>
            </div>

            {{-- Right: Component Previews --}}
            <div style="width:360px; display:flex; flex-direction:column; gap:20px; padding-top:10px;">
                
                {{-- Performance Card --}}
                <div style="background:var(--te-card); border-radius:{{ $cornerRadius }}px; padding:24px; box-shadow:0 20px 40px rgba(0,0,0,0.05); transition:border-radius 0.3s ease;">
                    <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
                        <div style="width:48px; height:48px; border-radius:12px; background:{{ $priHex }}15; display:flex; align-items:center; justify-content:center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="{{ $priHex }}" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div>
                            <p style="font-size:14px; font-weight:800; color:var(--te-text-h); margin:0 0 2px;">System Performance</p>
                            <p style="font-size:11px; color:var(--te-text-subtle); margin:0;">Real-time health telemetry</p>
                        </div>
                    </div>
                    <div style="height:6px; background:var(--te-surface); border-radius:4px; overflow:hidden;">
                        <div style="height:100%; width:82%; background:{{ $priHex }};"></div>
                    </div>
                </div>

                {{-- Small Metric Cards --}}
                <div style="display:flex; gap:16px;">
                    <div style="flex:1; background:{{ $secHex }}08; border:1px solid {{ $secHex }}25; border-radius:{{ $cornerRadius }}px; padding:20px; transition:border-radius 0.3s ease;">
                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $secHex }}; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <h3 style="font-size:24px; font-weight:800; color:{{ $secHex }}; margin:0 0 4px; letter-spacing:-.02em;">482</h3>
                        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:{{ $secHex }}; margin:0;">ACTIVE NODES</p>
                    </div>
                    
                    <div style="flex:1; background:{{ $accHex }}08; border:1px solid {{ $accHex }}25; border-radius:{{ $cornerRadius }}px; padding:20px; transition:border-radius 0.3s ease;">
                        <div style="width:24px; height:24px; border-radius:50%; background:transparent; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $accHex }}" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        </div>
                        <h3 style="font-size:24px; font-weight:800; color:{{ $accHex }}; margin:0 0 4px; letter-spacing:-.02em;">12</h3>
                        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:{{ $accHex }}; margin:0;">NATIONS</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Bottom Section: DNA Controls --}}
    <div class="te-dna-section" style="display:flex; gap:30px; margin-top:20px;">
        
        {{-- High-Density Color Engine --}}
        <div style="flex:2;">
            <h4>HIGH-DENSITY COLOR ENGINE</h4>
            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
                
                {{-- Primary Color Editor --}}
                <div class="te-dna-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:28px; height:28px; border-radius:50%; background:{{ $priHex }};"></div>
                            <span style="font-size:14px; font-weight:800; color:var(--te-text-h);">Primary</span>
                        </div>
                        <span style="font-size:10px; font-weight:800; font-family:monospace; color:{{ $priHex }};">{{ strtoupper($priHex) }}</span>
                    </div>
                    {{-- Sliders --}}
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">HUE</span>
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">{{ $priHue }}°</span>
                        </div>
                        <input type="range" class="te-slider pri" wire:model.live="priHue" min="0" max="360" style="margin-bottom:20px;">
                        
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">SAT</span>
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">{{ $priSat }}%</span>
                        </div>
                        <input type="range" class="te-slider pri" wire:model.live="priSat" min="0" max="100">
                    </div>
                </div>

                {{-- Secondary Color Editor --}}
                <div class="te-dna-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:28px; height:28px; border-radius:50%; background:{{ $secHex }};"></div>
                            <span style="font-size:14px; font-weight:800; color:var(--te-text-h);">Secondary</span>
                        </div>
                        <span style="font-size:10px; font-weight:800; font-family:monospace; color:{{ $secHex }};">{{ strtoupper($secHex) }}</span>
                    </div>
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">HUE</span>
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">{{ $secHue }}°</span>
                        </div>
                        <input type="range" class="te-slider sec" wire:model.live="secHue" min="0" max="360" style="margin-bottom:20px;">
                        
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">LIG</span>
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">{{ $secLig }}%</span>
                        </div>
                        <input type="range" class="te-slider sec" wire:model.live="secLig" min="0" max="100">
                    </div>
                </div>

                {{-- Accent Color Editor --}}
                <div class="te-dna-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:28px; height:28px; border-radius:50%; background:{{ $accHex }};"></div>
                            <span style="font-size:14px; font-weight:800; color:var(--te-text-h);">Accent</span>
                        </div>
                        <span style="font-size:10px; font-weight:800; font-family:monospace; color:{{ $accHex }};">{{ strtoupper($accHex) }}</span>
                    </div>
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">HUE</span>
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">{{ $accHue }}°</span>
                        </div>
                        <input type="range" class="te-slider acc" wire:model.live="accHue" min="0" max="360" style="margin-bottom:20px;">
                        
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">VIB</span>
                            <span style="font-size:10px; font-weight:700; color:var(--te-text-subtle);">{{ $accVib }}%</span>
                        </div>
                        <input type="range" class="te-slider acc" wire:model.live="accVib" min="0" max="100">
                    </div>
                </div>

            </div>
        </div>

        {{-- Visual DNA --}}
        <div style="flex:1;">
            <h4>VISUAL DNA</h4>
            <div class="te-dna-card" style="height:calc(100% - 44px);">
                
                {{-- Radius Slider --}}
                <div style="margin-bottom:34px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <span style="font-size:13px; font-weight:800; color:var(--te-text-h);">Corner Radius</span>
                        <span style="font-size:11px; font-weight:800; color:#10b981;">{{ $cornerRadius }}px</span>
                    </div>
                    <input type="range" class="te-slider rad" wire:model.live="cornerRadius" min="0" max="60" step="4">
                </div>

                {{-- Surface Contrast --}}
                <div>
                    <span style="display:block; font-size:13px; font-weight:800; color:var(--te-text-h); margin-bottom:14px;">Surface Contrast</span>
                    <div class="te-radio-group">
                        <button class="te-radio-btn {{ $contrastMode === 'Sophisticated' ? 'active' : '' }}" wire:click="ckSetContrast('Sophisticated')">Sophisticated</button>
                        <button class="te-radio-btn {{ $contrastMode === 'High Contrast' ? 'active' : '' }}" wire:click="ckSetContrast('High Contrast')">High Contrast</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</x-filament-panels::page>
