<x-filament-panels::page>
{{-- ═══════════════════════════════════════════════════════════════════
     SYSTEM USERS — Pixel-perfect replica of "All Users" design.
     Sections: Header → Table with checkboxes → Pagination → Bulk Action Bar
═══════════════════════════════════════════════════════════════════ --}}

<div
    style="font-family: 'Inter', 'Manrope', system-ui, -apple-system, sans-serif; max-width: 1400px; margin: 0 auto;"
    x-data="{
        showRoleMenu: false,
        showFilters: {{ $ckShowFilters ? 'true' : 'false' }},
    }"
>

    {{-- ═══════════════════════════════════════════════════════════════
         1. BREADCRUMB + PAGE HEADER
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom: 6px;">
        <p style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #a1a1aa; margin: 0;">
            <span style="color: #71717a;">Directory</span>
            <span style="margin: 0 6px; color: #d4d4d8;">›</span>
            <span style="color: #059669;">Global Users</span>
        </p>
    </div>

    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; gap: 20px;">
        <div style="flex: 1;">
            <h1 style="
                font-family: 'Manrope', 'Inter', system-ui, sans-serif;
                font-size: 34px;
                font-weight: 800;
                color: #18181b;
                margin: 0 0 10px 0;
                letter-spacing: -0.025em;
                line-height: 1.15;
            ">System Users</h1>
            <p style="
                font-size: 14px;
                color: #71717a;
                margin: 0;
                font-weight: 400;
                line-height: 1.6;
                max-width: 540px;
            ">Manage and monitor access across all 14 global modules and associated cultural organisations.</p>
        </div>

        <div style="display: flex; align-items: center; gap: 12px; flex-shrink: 0; margin-top: 6px;">
            {{-- Advanced Filters toggle --}}
            <button
                type="button"
                wire:click="$set('ckShowFilters', {{ $ckShowFilters ? 'false' : 'true' }})"
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 11px 20px;
                    border: 1.5px solid #e4e4e7;
                    border-radius: 14px;
                    background: {{ $ckShowFilters ? '#f0fdf4' : '#fff' }};
                    color: {{ $ckShowFilters ? '#059669' : '#3f3f46' }};
                    font-size: 13px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s;
                "
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Advanced Filters
            </button>

            {{-- Invite New User --}}
            <a
                href="{{ $createUrl }}"
                wire:navigate
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 11px 22px;
                    background: #18181b;
                    color: #fff;
                    font-size: 13px;
                    font-weight: 700;
                    border-radius: 14px;
                    text-decoration: none;
                    box-shadow: 0 4px 14px rgba(0,0,0,0.18);
                    transition: all 0.2s;
                    white-space: nowrap;
                "
                class="ck-invite-btn"
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Invite New User
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         2. ADVANCED FILTERS PANEL (collapsible)
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($ckShowFilters)
        <div style="
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(228,228,231,0.7);
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        ">
            {{-- Search --}}
            <div style="flex: 2; min-width: 200px;">
                <label style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #a1a1aa; margin-bottom: 6px;">SEARCH</label>
                <div style="position: relative;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a1a1aa" stroke-width="2" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input
                        wire:model.live.debounce.300ms="ckSearch"
                        type="text"
                        placeholder="Search by name or email..."
                        style="
                            width: 100%; box-sizing: border-box;
                            padding: 9px 12px 9px 32px;
                            border: 1px solid #e4e4e7;
                            border-radius: 10px;
                            font-size: 13px; color: #3f3f46;
                            outline: none; background: #fff;
                        "
                    >
                </div>
            </div>

            {{-- Role --}}
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #a1a1aa; margin-bottom: 6px;">SYSTEM ROLE</label>
                <select wire:model.live="ckRoleFilter" style="width: 100%; padding: 9px 12px; border: 1px solid #e4e4e7; border-radius: 10px; font-size: 13px; font-weight: 600; color: #3f3f46; background: #fff; outline: none;">
                    <option value="">All Roles</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="org_admin">Organisation Admin</option>
                    <option value="cms_editor">Content Editor</option>
                    <option value="teacher">Moderator / Teacher</option>
                    <option value="parent">Parent</option>
                </select>
            </div>

            {{-- Status --}}
            <div style="flex: 1; min-width: 130px;">
                <label style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #a1a1aa; margin-bottom: 6px;">STATUS</label>
                <select wire:model.live="ckStatusFilter" style="width: 100%; padding: 9px 12px; border: 1px solid #e4e4e7; border-radius: 10px; font-size: 13px; font-weight: 600; color: #3f3f46; background: #fff; outline: none;">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            {{-- Organisation --}}
            <div style="flex: 1; min-width: 160px;">
                <label style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #a1a1aa; margin-bottom: 6px;">ORGANISATION</label>
                <select wire:model.live="ckOrgFilter" style="width: 100%; padding: 9px 12px; border: 1px solid #e4e4e7; border-radius: 10px; font-size: 13px; font-weight: 600; color: #3f3f46; background: #fff; outline: none;">
                    <option value="">All Orgs</option>
                    @foreach ($organisations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset --}}
            <button
                wire:click="ckResetFilters"
                type="button"
                style="
                    display: inline-flex; align-items: center; gap: 6px;
                    padding: 9px 16px;
                    border: none; background: transparent;
                    color: #059669; font-size: 13px; font-weight: 600;
                    cursor: pointer; white-space: nowrap;
                "
            >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                Reset
            </button>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         3. USERS TABLE
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        background: rgba(255,255,255,0.97);
        border: 1px solid rgba(228,228,231,0.6);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    ">
        {{-- Table header --}}
        <div style="
            display: grid;
            grid-template-columns: 44px 2.5fr 1.4fr 1.3fr 0.9fr 1fr;
            gap: 0;
            padding: 14px 24px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #a1a1aa;
            border-bottom: 1px solid #f4f4f5;
            background: #fafafa;
            align-items: center;
        ">
            {{-- Select all checkbox --}}
            <div>
                <button
                    type="button"
                    wire:click="ckSelectAll"
                    style="
                        width: 18px; height: 18px;
                        border-radius: 6px;
                        border: 2px solid {{ count($ckSelected) > 0 ? '#059669' : '#d4d4d8' }};
                        background: {{ count($ckSelected) > 0 ? '#059669' : '#fff' }};
                        display: flex; align-items: center; justify-content: center;
                        cursor: pointer;
                        transition: all 0.15s;
                    "
                >
                    @if (count($ckSelected) > 0)
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    @endif
                </button>
            </div>
            <span>User Identity</span>
            <span>Organisation</span>
            <span>System Role</span>
            <span>Status</span>
            <span>Last Activity</span>
        </div>

        {{-- Rows --}}
        @forelse ($users as $user)
            @php
                $isSelected = in_array($user->id, $ckSelected);
                $isActive   = $user->is_active ?? true;

                // Role badge config (matches design colors)
                $roleBadge = match ($user->role) {
                    'super_admin' => [
                        'label' => 'SUPER USER',
                        'bg'    => 'rgba(251,191,36,0.15)',
                        'color' => '#b45309',
                        'border'=> 'rgba(251,191,36,0.4)',
                    ],
                    'org_admin' => [
                        'label' => 'ORGANISATION ADMIN',
                        'bg'    => 'rgba(167,139,250,0.15)',
                        'color' => '#6d28d9',
                        'border'=> 'rgba(167,139,250,0.4)',
                    ],
                    'cms_editor' => [
                        'label' => 'CONTENT EDITOR',
                        'bg'    => 'rgba(52,211,153,0.15)',
                        'color' => '#059669',
                        'border'=> 'rgba(52,211,153,0.4)',
                    ],
                    'teacher' => [
                        'label' => 'MODERATOR',
                        'bg'    => 'rgba(148,163,184,0.15)',
                        'color' => '#64748b',
                        'border'=> 'rgba(148,163,184,0.4)',
                    ],
                    'parent' => [
                        'label' => 'PARENT',
                        'bg'    => 'rgba(96,165,250,0.12)',
                        'color' => '#2563eb',
                        'border'=> 'rgba(96,165,250,0.35)',
                    ],
                    default => [
                        'label' => strtoupper($user->role ?? 'USER'),
                        'bg'    => '#f4f4f5',
                        'color' => '#71717a',
                        'border'=> '#e4e4e7',
                    ],
                };

                // Avatar initials + gradient
                $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr(strrchr($user->name, ' '), 1, 1) : substr($user->name, 1, 1)));
                $avatarGradients = [
                    'linear-gradient(135deg, #059669, #10b981)',
                    'linear-gradient(135deg, #7c3aed, #a78bfa)',
                    'linear-gradient(135deg, #d97706, #f59e0b)',
                    'linear-gradient(135deg, #dc2626, #f87171)',
                    'linear-gradient(135deg, #0284c7, #38bdf8)',
                    'linear-gradient(135deg, #db2777, #f472b6)',
                ];
                $avatarGradient = $avatarGradients[$user->id % count($avatarGradients)];

                // Last activity from audit log
                $lastAt = $lastActivity[$user->id] ?? null;
                if ($lastAt) {
                    try {
                        $lastLabel = \Carbon\Carbon::parse($lastAt)->diffForHumans();
                    } catch (\Exception $e) {
                        $lastLabel = 'Recently';
                    }
                } else {
                    $lastLabel = 'Never';
                }

                // Email truncation
                $emailShort = strlen($user->email) > 28 ? substr($user->email, 0, 26) . '…' : $user->email;
            @endphp

            <div
                style="
                    display: grid;
                    grid-template-columns: 44px 2.5fr 1.4fr 1.3fr 0.9fr 1fr;
                    gap: 0;
                    padding: 16px 24px;
                    align-items: center;
                    border-bottom: 1px solid #f9f9f9;
                    background: {{ $isSelected ? 'rgba(240,253,244,0.6)' : 'transparent' }};
                    transition: background 0.15s;
                "
                class="ck-user-row-hover"
            >
                {{-- Checkbox --}}
                <div>
                    <button
                        type="button"
                        wire:click="ckToggleUser({{ $user->id }})"
                        style="
                            width: 20px; height: 20px;
                            border-radius: 6px;
                            border: 2px solid {{ $isSelected ? '#059669' : '#d4d4d8' }};
                            background: {{ $isSelected ? '#059669' : '#fff' }};
                            display: flex; align-items: center; justify-content: center;
                            cursor: pointer;
                            transition: all 0.15s;
                            flex-shrink: 0;
                        "
                    >
                        @if ($isSelected)
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5"><polyline points="20 6 9 17 4 12"/></svg>
                        @endif
                    </button>
                </div>

                {{-- User Identity --}}
                <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
                    {{-- Avatar --}}
                    <div style="
                        width: 42px; height: 42px;
                        border-radius: 50%;
                        background: {{ $avatarGradient }};
                        display: flex; align-items: center; justify-content: center;
                        font-size: 14px; font-weight: 800; color: #fff;
                        flex-shrink: 0;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                        position: relative;
                    ">
                        {{ $initials }}
                        @if ($isActive)
                            <span style="
                                position: absolute; bottom: 1px; right: 1px;
                                width: 10px; height: 10px;
                                border-radius: 50%;
                                background: #10b981;
                                border: 2px solid #fff;
                            "></span>
                        @endif
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <p style="font-size: 14px; font-weight: 700; color: #18181b; margin: 0; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->name }}</p>
                        <p style="font-size: 12px; color: #a1a1aa; margin: 2px 0 0 0; font-weight: 400; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $emailShort }}</p>
                    </div>
                </div>

                {{-- Organisation --}}
                <div>
                    <p style="font-size: 13px; font-weight: 600; color: #3f3f46; margin: 0; line-height: 1.4;">
                        {{ $user->organisation?->name ?? '—' }}
                    </p>
                </div>

                {{-- System Role badge --}}
                <div>
                    <span style="
                        display: inline-block;
                        padding: 5px 12px;
                        border-radius: 20px;
                        border: 1px solid {{ $roleBadge['border'] }};
                        background: {{ $roleBadge['bg'] }};
                        color: {{ $roleBadge['color'] }};
                        font-size: 10px;
                        font-weight: 800;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                        white-space: nowrap;
                    ">{{ $roleBadge['label'] }}</span>
                </div>

                {{-- Status --}}
                <div>
                    <span style="
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        font-size: 13px;
                        font-weight: 600;
                        color: {{ $isActive ? '#059669' : '#a1a1aa' }};
                    ">
                        <span style="
                            width: 7px; height: 7px;
                            border-radius: 50%;
                            background: {{ $isActive ? '#10b981' : '#d4d4d8' }};
                            display: inline-block;
                            {{ $isActive ? 'box-shadow: 0 0 5px rgba(16,185,129,0.5);' : '' }}
                        "></span>
                        {{ $isActive ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                {{-- Last Activity --}}
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 12px; color: #71717a; font-weight: 500;">{{ $lastLabel }}</span>

                    {{-- Row action buttons (visible on hover) --}}
                    <div style="display: flex; gap: 6px; opacity: 0; transition: opacity 0.15s;" class="ck-row-actions">
                        <a
                            href="{{ \App\Filament\Resources\UserResource::getUrl('edit', ['record' => $user]) }}"
                            wire:navigate
                            title="Edit user"
                            style="
                                width: 28px; height: 28px;
                                border-radius: 8px;
                                border: 1px solid #e4e4e7;
                                background: #fff;
                                display: flex; align-items: center; justify-content: center;
                                color: #71717a;
                                text-decoration: none;
                                transition: all 0.15s;
                            "
                            class="ck-action-btn"
                        >
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        @if ($isActive)
                            <button
                                type="button"
                                wire:click="ckSuspendUser({{ $user->id }})"
                                wire:confirm="Suspend {{ $user->name }}?"
                                title="Suspend user"
                                style="
                                    width: 28px; height: 28px;
                                    border-radius: 8px;
                                    border: 1px solid #fecaca;
                                    background: #fef2f2;
                                    display: flex; align-items: center; justify-content: center;
                                    color: #dc2626;
                                    cursor: pointer;
                                    transition: all 0.15s;
                                "
                                class="ck-action-btn"
                            >
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg>
                            </button>
                        @else
                            <button
                                type="button"
                                wire:click="ckActivateUser({{ $user->id }})"
                                wire:confirm="Activate {{ $user->name }}?"
                                title="Activate user"
                                style="
                                    width: 28px; height: 28px;
                                    border-radius: 8px;
                                    border: 1px solid #bbf7d0;
                                    background: #f0fdf4;
                                    display: flex; align-items: center; justify-content: center;
                                    color: #059669;
                                    cursor: pointer;
                                "
                                class="ck-action-btn"
                            >
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div style="padding: 60px 20px; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d4d4d8" stroke-width="1.5" style="margin: 0 auto 16px; display: block;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <p style="font-size: 16px; font-weight: 700; color: #71717a; margin: 0;">No system users found</p>
                <p style="font-size: 13px; color: #a1a1aa; margin: 6px 0 0 0;">Try adjusting your filters or invite the first user.</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         4. FOOTER — COUNT + PAGINATION
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 20px;
        padding: 0 4px;
        padding-bottom: 80px;
    ">
        <p style="font-size: 13px; color: #71717a; font-weight: 500; margin: 0;">
            Showing
            <span style="font-weight: 700; color: #18181b;">{{ count($users) }}</span>
            of
            <span style="font-weight: 700; color: #18181b;">{{ number_format($totalUsers) }}</span>
            system users
        </p>

        <div style="display: flex; align-items: center; gap: 4px;">
            <button type="button" style="padding: 7px 12px; font-size: 13px; font-weight: 500; color: #d4d4d8; background: transparent; border: none; cursor: default;">‹</button>
            <button type="button" style="
                width: 34px; height: 34px; border-radius: 50%;
                font-size: 13px; font-weight: 700; border: none; cursor: pointer;
                background: #18181b; color: #fff;
            ">1</button>
            <button type="button" style="
                width: 34px; height: 34px; border-radius: 50%;
                font-size: 13px; font-weight: 600; border: 1px solid #e4e4e7;
                background: #fff; color: #3f3f46; cursor: pointer;
            ">2</button>
            <button type="button" style="
                width: 34px; height: 34px; border-radius: 50%;
                font-size: 13px; font-weight: 600; border: 1px solid #e4e4e7;
                background: #fff; color: #3f3f46; cursor: pointer;
            ">3</button>
            <span style="padding: 0 6px; color: #a1a1aa; font-weight: 600; font-size: 13px;">…</span>
            <button type="button" style="padding: 7px 12px; font-size: 13px; font-weight: 600; color: #3f3f46; background: transparent; border: none; cursor: pointer;">›</button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         5. BULK ACTION BAR (slides up when users selected)
    ═══════════════════════════════════════════════════════════════ --}}
    @if (count($ckSelected) > 0)
        <div
            style="
                position: fixed;
                bottom: 32px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 100;
                animation: ckSlideUp 0.25s ease-out;
            "
        >
            <div style="
                display: flex;
                align-items: center;
                gap: 0;
                background: linear-gradient(135deg, #92400e 0%, #b45309 50%, #d97706 100%);
                border-radius: 40px;
                padding: 14px 8px;
                box-shadow: 0 8px 32px rgba(180,83,9,0.45);
                white-space: nowrap;
            ">
                {{-- Count --}}
                <div style="
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 0 20px 0 16px;
                    border-right: 1px solid rgba(255,255,255,0.2);
                ">
                    <span style="font-size: 22px; font-weight: 800; color: #fff; line-height: 1;">{{ count($ckSelected) }}</span>
                    <span style="font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.75); margin-top: 2px;">USERS SELECTED</span>
                </div>

                {{-- Change Role --}}
                <div style="position: relative;" x-data="{ open: false }">
                    <button
                        type="button"
                        @click="open = !open"
                        style="
                            display: flex; align-items: center; gap: 8px;
                            padding: 0 20px;
                            border: none; background: transparent;
                            color: #fff; font-size: 12px; font-weight: 700;
                            text-transform: uppercase; letter-spacing: 0.05em;
                            cursor: pointer;
                        "
                    >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        CHANGE ROLE
                    </button>
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition
                        style="
                            position: absolute; bottom: calc(100% + 10px); left: 50%;
                            transform: translateX(-50%);
                            background: #18181b; border-radius: 14px;
                            padding: 8px;
                            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
                            min-width: 180px;
                            z-index: 200;
                        "
                    >
                        @foreach ([
                            'super_admin' => 'Super Admin',
                            'org_admin'   => 'Organisation Admin',
                            'cms_editor'  => 'Content Editor',
                            'teacher'     => 'Moderator',
                            'parent'      => 'Parent',
                        ] as $roleKey => $roleLabel)
                            <button
                                type="button"
                                wire:click="ckChangeRole('{{ $roleKey }}')"
                                @click="open = false"
                                style="
                                    display: block; width: 100%;
                                    padding: 9px 14px;
                                    background: transparent;
                                    border: none; border-radius: 10px;
                                    text-align: left;
                                    color: #d4d4d8; font-size: 12px;
                                    font-weight: 600; cursor: pointer;
                                    transition: background 0.15s;
                                "
                                class="ck-role-option"
                            >{{ $roleLabel }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Divider --}}
                <div style="width: 1px; height: 28px; background: rgba(255,255,255,0.2);"></div>

                {{-- Suspend --}}
                <button
                    type="button"
                    wire:click="ckSuspendSelected"
                    wire:confirm="Suspend {{ count($ckSelected) }} selected user(s)?"
                    style="
                        display: flex; align-items: center; gap: 8px;
                        padding: 0 20px;
                        border: none; background: transparent;
                        color: #fff; font-size: 12px; font-weight: 700;
                        text-transform: uppercase; letter-spacing: 0.05em;
                        cursor: pointer;
                    "
                >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg>
                    SUSPEND
                </button>

                {{-- Divider --}}
                <div style="width: 1px; height: 28px; background: rgba(255,255,255,0.2);"></div>

                {{-- Delete --}}
                <button
                    type="button"
                    wire:click="ckDeleteSelected"
                    wire:confirm="Permanently delete {{ count($ckSelected) }} selected user(s)? This cannot be undone."
                    style="
                        display: flex; align-items: center; gap: 8px;
                        padding: 0 20px;
                        border: none; background: transparent;
                        color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 700;
                        text-transform: uppercase; letter-spacing: 0.05em;
                        cursor: pointer;
                    "
                >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    DELETE
                </button>

                {{-- Divider --}}
                <div style="width: 1px; height: 28px; background: rgba(255,255,255,0.2);"></div>

                {{-- Dismiss --}}
                <button
                    type="button"
                    wire:click="$set('ckSelected', [])"
                    style="
                        width: 36px; height: 36px;
                        border-radius: 50%;
                        background: rgba(255,255,255,0.15);
                        border: none;
                        display: flex; align-items: center; justify-content: center;
                        color: #fff; font-size: 18px;
                        cursor: pointer;
                        margin-right: 6px;
                        transition: background 0.15s;
                    "
                    class="ck-dismiss-btn"
                >×</button>
            </div>
        </div>
    @endif

</div>

{{-- ═══════════════════════════════════════════════════════════════
     STYLES
═══════════════════════════════════════════════════════════════ --}}
<style>
    @keyframes ckSlideUp {
        from { opacity: 0; transform: translateX(-50%) translateY(16px); }
        to   { opacity: 1; transform: translateX(-50%) translateY(0); }
    }

    .ck-user-row-hover:hover {
        background: rgba(248,250,252,0.8) !important;
    }
    .ck-user-row-hover:hover .ck-row-actions {
        opacity: 1 !important;
    }
    .ck-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .ck-invite-btn:hover {
        background: #27272a !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.25) !important;
    }
    .ck-role-option:hover {
        background: rgba(255,255,255,0.1) !important;
        color: #fff !important;
    }
    .ck-dismiss-btn:hover {
        background: rgba(255,255,255,0.25) !important;
    }
    /* Hide default Filament page header & breadcrumbs */
    .fi-page-header,
    .fi-breadcrumbs,
    nav[aria-label="Breadcrumbs"] {
        display: none !important;
    }
</style>
</x-filament-panels::page>
