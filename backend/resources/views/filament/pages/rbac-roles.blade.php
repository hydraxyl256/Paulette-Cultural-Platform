<x-filament-panels::page>
{{-- ════════════════════════════════════════════════════════════════════
     RBAC ROLES — Access Control & Permissions
     Pixel-perfect · Dark / Light mode
     Layout: Header → Roles Table (left) + Role Permissions panel (right)
             → Bottom KPI cards
════════════════════════════════════════════════════════════════════ --}}

<style>
/* ── Light tokens ── */
:root {
    --rb-card:         #ffffff;
    --rb-card-hover:   #f8fafc;
    --rb-border:       rgba(228,228,231,0.8);
    --rb-border-inner: #f1f5f9;
    --rb-text-h:       #18181b;
    --rb-text-body:    #3f3f46;
    --rb-text-muted:   #71717a;
    --rb-text-subtle:  #a1a1aa;
    --rb-input-bg:     #f9fafb;
    --rb-input-border: #e4e4e7;
    --rb-surface:      rgba(248,250,252,0.95);
    --rb-shadow-sm:    0 1px 4px rgba(0,0,0,0.05);
    --rb-shadow-md:    0 4px 18px rgba(0,0,0,0.08);
    --rb-shadow-lg:    0 10px 32px rgba(0,0,0,0.12);
    --rb-row-hover:    rgba(248,250,252,0.9);
    --rb-row-sel:      rgba(5,150,105,0.05);
    --rb-row-sel-border: rgba(5,150,105,0.2);
    --rb-perm-check:   #059669;
    --rb-tbl-hdr:      rgba(248,250,252,0.95);
}
.dark {
    --rb-card:         #1c1c27;
    --rb-card-hover:   #22222f;
    --rb-border:       rgba(63,63,70,0.85);
    --rb-border-inner: rgba(39,39,42,0.9);
    --rb-text-h:       #f4f4f5;
    --rb-text-body:    #d4d4d8;
    --rb-text-muted:   #a1a1aa;
    --rb-text-subtle:  #52525b;
    --rb-input-bg:     rgba(39,39,42,0.8);
    --rb-input-border: rgba(63,63,70,0.9);
    --rb-surface:      rgba(24,24,35,0.9);
    --rb-shadow-sm:    0 1px 6px rgba(0,0,0,0.4);
    --rb-shadow-md:    0 4px 20px rgba(0,0,0,0.45);
    --rb-shadow-lg:    0 10px 36px rgba(0,0,0,0.6);
    --rb-row-hover:    rgba(34,34,47,0.9);
    --rb-row-sel:      rgba(52,211,153,0.07);
    --rb-row-sel-border: rgba(52,211,153,0.25);
    --rb-perm-check:   #34d399;
    --rb-tbl-hdr:      rgba(28,28,39,0.95);
}

/* Chrome override */
.fi-page-header,.fi-breadcrumbs,nav[aria-label="Breadcrumbs"]{ display:none!important; }

/* Root */
.rb-root { font-family:'Inter','Manrope',system-ui,-apple-system,sans-serif; max-width:1320px; margin:0 auto; }

/* Hovers */
.rb-role-row { transition:background .12s, border-color .12s; cursor:pointer; }
.rb-role-row:hover { background:var(--rb-row-hover)!important; }

/* Toggle */
.rb-toggle { display:inline-flex; align-items:center; cursor:pointer; }
.rb-toggle input { display:none; }
.rb-toggle-track { width:38px; height:21px; border-radius:99px; transition:background .2s; }
.rb-toggle input:checked ~ .rb-toggle-track { background:#059669; }
.dark .rb-toggle input:checked ~ .rb-toggle-track { background:#34d399; }
.rb-toggle input:not(:checked) ~ .rb-toggle-track { background:#d1d5db; }
.dark .rb-toggle input:not(:checked) ~ .rb-toggle-track { background:#3f3f46; }
.rb-toggle-thumb { position:absolute; top:2px; width:17px; height:17px; border-radius:50%; background:#fff; box-shadow:0 1px 4px rgba(0,0,0,0.2); transition:left .2s; pointer-events:none; }

/* Buttons */
.rb-create-btn:hover { background:#047857!important; transform:translateY(-2px); box-shadow:0 8px 28px rgba(5,150,105,.4)!important; }
.rb-save-btn:hover { background:#047857!important; }
.rb-audit-btn:hover { background:var(--rb-border-inner)!important; }
.rb-filter-input:focus { border-color:#059669!important; box-shadow:0 0 0 3px rgba(5,150,105,.12)!important; outline:none; }

/* Security dots */
.rb-dot { width:9px; height:9px; border-radius:50%; display:inline-block; }
</style>

<div class="rb-root">

{{-- ════════════════════════════════════════════════════════════════
     1 · PAGE HEADER
════════════════════════════════════════════════════════════════ --}}
<div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:28px; gap:16px;">
    <div>
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
            <span style="font-size:11px; font-weight:600; color:var(--rb-text-subtle);">System</span>
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--rb-text-subtle)" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="font-size:11px; font-weight:700; color:#059669;">RBAC Roles</span>
        </div>
        <h1 style="font-size:34px; font-weight:800; color:var(--rb-text-h); margin:0 0 10px; letter-spacing:-.04em; line-height:1.05;">
            Access Control
        </h1>
        <p style="font-size:13px; color:var(--rb-text-muted); margin:0; max-width:500px; line-height:1.6;">
            Manage organizational hierarchies and fine-grained permission<br>matrices across the Curator ecosystem.
        </p>
    </div>

    <div style="display:flex; align-items:center; gap:10px; flex-shrink:0; margin-top:8px;">
        {{-- Audit Logs --}}
        <a href="{{ $auditUrl }}" wire:navigate class="rb-audit-btn" style="
            display:inline-flex; align-items:center; gap:7px;
            padding:11px 20px;
            background:var(--rb-card); color:var(--rb-text-body);
            font-size:13px; font-weight:700;
            border-radius:12px; text-decoration:none;
            border:1px solid var(--rb-border);
            box-shadow:var(--rb-shadow-sm);
            transition:all .15s;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Audit Logs
        </a>

        {{-- Create Role --}}
        <a href="{{ $createUrl }}" wire:navigate class="rb-create-btn" style="
            display:inline-flex; align-items:center; gap:9px;
            padding:11px 22px;
            background:#059669; color:#fff;
            font-size:13px; font-weight:700;
            border-radius:12px; text-decoration:none;
            box-shadow:0 4px 18px rgba(5,150,105,.3);
            transition:all .2s;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            {{ $totalRoles }} Create Role
        </a>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     2 · SEARCH
════════════════════════════════════════════════════════════════ --}}
<div style="margin-bottom:18px; max-width:400px; position:relative;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--rb-text-subtle)" stroke-width="2"
         style="position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none;">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
    </svg>
    <input wire:model.live.debounce.300ms="ckSearch" type="text"
           placeholder="Search permissions, roles, or users..."
           class="rb-filter-input"
           style="
               width:100%; box-sizing:border-box;
               padding:10px 14px 10px 36px;
               border:1px solid var(--rb-border); border-radius:40px;
               font-size:13px; font-weight:500;
               color:var(--rb-text-body); background:var(--rb-card);
               transition:border-color .15s, box-shadow .15s;
           ">
</div>

{{-- ════════════════════════════════════════════════════════════════
     3 · MAIN 2-COL LAYOUT: Roles table (left) + Permissions (right)
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:1fr 360px; gap:20px; margin-bottom:24px;">

    {{-- ── LEFT: Roles table ── --}}
    <div style="
        background:var(--rb-card); border:1px solid var(--rb-border);
        border-radius:22px; overflow:hidden;
        box-shadow:var(--rb-shadow-md);
    ">
        {{-- Column headers --}}
        <div style="
            display:grid; grid-template-columns:220px 1fr 110px 110px;
            padding:13px 24px; background:var(--rb-tbl-hdr);
            border-bottom:1px solid var(--rb-border-inner);
            align-items:center;
        ">
            <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--rb-text-subtle);">Role Name</span>
            <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--rb-text-subtle);">Description</span>
            <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--rb-text-subtle);">Users</span>
            <span style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.12em; color:var(--rb-text-subtle);">Security</span>
        </div>

        {{-- Role rows --}}
        @php
            $roleMeta = [
                'Super Admin'    => ['type' => 'SYSTEM LEVEL',  'color' => '#7c3aed', 'bg' => 'rgba(124,58,237,0.08)',  'security' => 4, 'desc' => 'Full system override capabilities with unrestricted database access.'],
                'Content Editor' => ['type' => 'ACTIVE VIEW',   'color' => '#059669', 'bg' => 'rgba(5,150,105,0.08)',   'security' => 3, 'desc' => 'Manages exhibits, metadata, and collection lifecycle.'],
                'Org Admin'      => ['type' => 'SCOPED',        'color' => '#d97706', 'bg' => 'rgba(217,119,6,0.08)',   'security' => 3, 'desc' => 'Administrator for specific organizational branches only.'],
                'Moderator'      => ['type' => 'SUPPORT',       'color' => '#6b7280', 'bg' => 'rgba(107,114,128,0.08)', 'security' => 1, 'desc' => 'Handles public comments and community interactions.'],
            ];
            $securityColors = ['#7c3aed','#059669','#d97706','#6b7280'];
        @endphp

        @forelse ($roles as $role)
        @php
            $meta    = $roleMeta[$role->name] ?? ['type' => 'CUSTOM', 'color' => '#059669', 'bg' => 'rgba(5,150,105,0.08)', 'security' => 2, 'desc' => 'Custom role with specific permissions.'];
            $isSel   = $selectedRole && $selectedRole->id === $role->id;
            $secDots = $meta['security'];
            $dotColor = $meta['color'];
            $userCount = $role->user_count;
        @endphp
        <div class="rb-role-row"
             wire:click="ckSelectRole({{ $role->id }})"
             style="
                 display:grid; grid-template-columns:220px 1fr 110px 110px;
                 padding:18px 24px;
                 align-items:center;
                 border-bottom:1px solid var(--rb-border-inner);
                 border-left:3px solid {{ $isSel ? $meta['color'] : 'transparent' }};
                 background:{{ $isSel ? 'var(--rb-row-sel)' : 'transparent' }};
             ">

            {{-- Role name + badge --}}
            <div>
                <p style="font-size:15px; font-weight:700; color:var(--rb-text-h); margin:0 0 5px;">{{ $role->name }}</p>
                <span style="
                    display:inline-block; padding:3px 10px;
                    background:{{ $meta['bg'] }}; color:{{ $meta['color'] }};
                    font-size:9px; font-weight:800; letter-spacing:.1em;
                    border-radius:20px; text-transform:uppercase;
                ">{{ $meta['type'] }}</span>
            </div>

            {{-- Description --}}
            <p style="font-size:12px; color:var(--rb-text-muted); margin:0; line-height:1.5; padding-right:12px;">
                {{ $meta['desc'] }}
            </p>

            {{-- Users --}}
            <div>
                @if ($userCount > 0)
                    <div style="display:flex; align-items:center; gap:4px;">
                        {{-- Mini avatar stack --}}
                        @php $initials = ['A','B']; @endphp
                        @foreach (array_slice($initials, 0, min(2, $userCount)) as $i => $ini)
                            <div style="
                                width:24px; height:24px; border-radius:50%;
                                background:{{ ['#059669','#7c3aed','#d97706'][$i % 3] }};
                                border:2px solid var(--rb-card);
                                margin-left:{{ $i > 0 ? '-6px' : '0' }};
                                font-size:9px; font-weight:800; color:#fff;
                                display:flex; align-items:center; justify-content:center;
                            ">{{ $ini }}</div>
                        @endforeach
                        @if ($userCount > 2)
                            <span style="font-size:11px; font-weight:700; color:{{ $meta['color'] }}; margin-left:4px;">+{{ $userCount - 2 }}</span>
                        @endif
                    </div>
                @else
                    <span style="font-size:12px; color:var(--rb-text-subtle);">{{ $userCount }} Users</span>
                @endif
            </div>

            {{-- Security level dots --}}
            <div style="display:flex; align-items:center; gap:4px;">
                @for ($d = 1; $d <= 4; $d++)
                    <div class="rb-dot" style="background:{{ $d <= $secDots ? $dotColor : 'var(--rb-border)' }};"></div>
                @endfor
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:48px 20px;">
            <p style="font-size:36px; margin:0 0 12px;">🛡️</p>
            <p style="font-size:14px; font-weight:700; color:var(--rb-text-muted); margin:0 0 8px;">No roles found</p>
            <a href="{{ $createUrl }}" wire:navigate style="font-size:13px; font-weight:700; color:#059669; text-decoration:none;">+ Create your first role</a>
        </div>
        @endforelse
    </div>

    {{-- ── RIGHT: Role Permissions Panel ── --}}
    <div style="
        background:var(--rb-card); border:1px solid var(--rb-border);
        border-radius:22px; padding:22px;
        box-shadow:var(--rb-shadow-md);
        display:flex; flex-direction:column;
    ">
        {{-- Panel header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;">
            <h2 style="font-size:17px; font-weight:800; color:var(--rb-text-h); margin:0; letter-spacing:-.02em;">Role Permissions</h2>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--rb-text-subtle)" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        @if ($selectedRole)
        {{-- Selected role chip --}}
        @php
            $selMeta = $roleMeta[$selectedRole->name] ?? ['type' => 'CUSTOM', 'color' => '#059669', 'bg' => 'rgba(5,150,105,0.08)', 'security' => 2, 'desc' => ''];
            $permCount = $selectedRole->permissions->count();
        @endphp
        <div style="display:flex; align-items:center; gap:12px; padding:12px 14px; background:var(--rb-row-sel); border:1px solid var(--rb-row-sel-border); border-radius:14px; margin-bottom:20px;">
            <div style="
                width:38px; height:38px; border-radius:10px; flex-shrink:0;
                background:{{ $selMeta['bg'] }};
                display:flex; align-items:center; justify-content:center;
            ">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $selMeta['color'] }}" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <div>
                <p style="font-size:14px; font-weight:800; color:var(--rb-text-h); margin:0;">{{ $selectedRole->name }}</p>
                <p style="font-size:11px; color:var(--rb-text-muted); margin:2px 0 0;">Editing {{ $permCount }} active grants</p>
            </div>
        </div>

        {{-- Permission groups --}}
        @foreach ($permissionGroups as $groupName => $perms)
        <div style="margin-bottom:18px;">
            <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:var(--rb-text-subtle); margin:0 0 10px;">{{ $groupName }}</p>

            @foreach ($perms as $permName)
            @php
                $granted  = $permissionStates[$permName] ?? false;
                $changed  = array_key_exists($permName, $ckChangedPerms);
                $readableName = ucwords(str_replace(['-', ':'], [' ', ': '], $permName));
            @endphp
            <div style="
                display:flex; align-items:center; justify-content:space-between;
                padding:9px 0;
                border-bottom:1px solid var(--rb-border-inner);
                {{ $changed ? 'opacity:.9;' : '' }}
            ">
                <div style="display:flex; align-items:center; gap:10px;">
                    {{-- Checkbox circle --}}
                    <button type="button" wire:click="ckTogglePerm('{{ $permName }}')"
                            style="
                                width:20px; height:20px; border-radius:50%; flex-shrink:0;
                                border:2px solid {{ $granted ? 'var(--rb-perm-check)' : 'var(--rb-border)' }};
                                background:{{ $granted ? 'var(--rb-perm-check)' : 'transparent' }};
                                cursor:pointer; display:flex; align-items:center; justify-content:center;
                                transition:all .15s; padding:0;
                            ">
                        @if ($granted)
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @endif
                    </button>
                    <span style="font-size:13px; font-weight:{{ $granted ? '600' : '500' }}; color:{{ $granted ? 'var(--rb-text-body)' : 'var(--rb-text-subtle)' }}; text-decoration:{{ $granted ? 'none' : 'none' }};">{{ $readableName }}</span>
                </div>
                {{-- Info dot --}}
                <div style="width:7px; height:7px; border-radius:50%; background:var(--rb-border); flex-shrink:0;"></div>
            </div>
            @endforeach
        </div>
        @endforeach

        {{-- Push to bottom --}}
        <div style="flex:1;"></div>

        {{-- Save / Discard buttons --}}
        <div style="display:flex; gap:8px; padding-top:16px; border-top:1px solid var(--rb-border-inner);">
            <button type="button" wire:click="ckSaveChanges"
                    class="rb-save-btn"
                    wire:loading.attr="disabled" wire:target="ckSaveChanges"
                    style="
                        flex:1; padding:11px; border-radius:12px; border:none; cursor:pointer;
                        background:#059669; color:#fff;
                        font-size:13px; font-weight:700;
                        transition:all .15s;
                        {{ !$hasChanges ? 'opacity:.6;' : '' }}
                    ">
                <span wire:loading.remove wire:target="ckSaveChanges">Save Changes</span>
                <span wire:loading wire:target="ckSaveChanges">Saving...</span>
            </button>
            <button type="button" wire:click="ckDiscard"
                    style="
                        padding:11px 18px; border-radius:12px; cursor:pointer;
                        background:var(--rb-card); color:var(--rb-text-muted);
                        border:1px solid var(--rb-border);
                        font-size:13px; font-weight:700;
                        transition:all .15s;
                    ">
                Discard
            </button>
        </div>

        @else
        <div style="text-align:center; padding:40px 16px; flex:1; display:flex; align-items:center; justify-content:center; flex-direction:column;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--rb-text-subtle)" stroke-width="1.5" style="margin-bottom:12px;">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <p style="font-size:13px; color:var(--rb-text-subtle); margin:0;">Select a role to manage permissions</p>
        </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     4 · BOTTOM KPI CARDS (3 cards)
════════════════════════════════════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:18px; margin-bottom:20px;">

    {{-- Secure Core (dark green) --}}
    <div style="
        background:linear-gradient(145deg,#052e16 0%,#065f46 60%,#059669 100%);
        border-radius:20px; padding:24px;
        box-shadow:0 6px 24px rgba(5,150,105,0.35);
        position:relative; overflow:hidden;
    ">
        <div style="position:absolute; top:-20px; right:-20px; width:90px; height:90px; border-radius:50%; background:rgba(255,255,255,0.06);"></div>
        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.16em; color:rgba(255,255,255,0.55); margin:0 0 8px;">Secure Core</p>
        <p style="font-size:38px; font-weight:800; color:#fff; margin:0 0 10px; letter-spacing:-.04em;">{{ $secureCore }}%</p>
        <p style="font-size:12px; color:rgba(255,255,255,0.65); margin:0; line-height:1.5;">
            Roles mapped to verified identity providers with MFA enforcement.
        </p>
    </div>

    {{-- User Distribution (light card) --}}
    <div style="
        background:var(--rb-card); border:1px solid var(--rb-border);
        border-radius:20px; padding:24px;
        box-shadow:var(--rb-shadow-sm);
    ">
        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.16em; color:var(--rb-text-subtle); margin:0 0 8px;">User Distribution</p>
        <p style="font-size:38px; font-weight:800; color:var(--rb-text-h); margin:0 0 8px; letter-spacing:-.04em;">{{ $totalUsers }}</p>
        {{-- Mini bar --}}
        <div style="height:5px; background:var(--rb-border-inner); border-radius:99px; overflow:hidden; margin-bottom:8px;">
            <div style="height:100%; width:72%; background:#d97706; border-radius:99px;"></div>
        </div>
        <p style="font-size:11px; font-weight:600; color:var(--rb-text-muted); margin:0;">{{ $activeUsers }} Active</p>
    </div>

    {{-- System Health (white) --}}
    <div style="
        background:var(--rb-card); border:1px solid var(--rb-border);
        border-radius:20px; padding:24px;
        box-shadow:var(--rb-shadow-sm);
    ">
        <p style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.16em; color:#059669; margin:0 0 8px;">System Health</p>
        <p style="font-size:34px; font-weight:800; color:var(--rb-text-h); margin:0 0 8px; letter-spacing:-.03em;">Optimal</p>
        <p style="font-size:12px; color:var(--rb-text-muted); margin:0; line-height:1.5;">
            No role conflicts detected in latest 24h sync.
        </p>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     5 · FLOATING ACTION BUTTON (purple lightning bolt)
════════════════════════════════════════════════════════════════ --}}
<div style="position:fixed; bottom:28px; right:28px; z-index:200;">
    <button type="button"
            title="Quick Actions"
            style="
                width:52px; height:52px; border-radius:50%; border:none; cursor:pointer;
                background:linear-gradient(145deg,#7c3aed,#5b21b6);
                display:flex; align-items:center; justify-content:center;
                box-shadow:0 6px 24px rgba(124,58,237,0.5);
                transition:transform .2s, box-shadow .2s;
            "
            onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 8px 32px rgba(124,58,237,0.65)'"
            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 24px rgba(124,58,237,0.5)'">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff" stroke="none">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
        </svg>
    </button>
</div>

</div>{{-- .rb-root --}}
</x-filament-panels::page>
