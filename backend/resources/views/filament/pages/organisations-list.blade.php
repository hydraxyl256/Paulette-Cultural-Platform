<x-filament-panels::page>
{{-- ═══════════════════════════════════════════════════════════════════
     ORGANISATIONS — Pixel-perfect replica of product designer's mockup.
     Sections: Header → Filters + Counter → Table → Pagination
═══════════════════════════════════════════════════════════════════ --}}

<div
    style="font-family: 'Inter', 'Manrope', system-ui, -apple-system, sans-serif; max-width: 1400px; margin: 0 auto;"
    x-data="{ showActions: null }"
>
    {{-- ═══════════════════════════════════════════════════════════════
         1. PAGE HEADER
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px;">
        <div style="flex: 1;">
            <h1 style="
                font-family: 'Manrope', 'Inter', system-ui, sans-serif;
                font-size: 36px;
                font-weight: 800;
                color: #18181b;
                margin: 0;
                letter-spacing: -0.025em;
            ">Organisations</h1>
            <p style="
                font-size: 15px;
                color: #71717a;
                margin: 8px 0 0 0;
                font-weight: 400;
                line-height: 1.5;
                max-width: 520px;
            ">Manage institutional access, subscription tiers, and cultural entity settings.</p>
        </div>

        {{-- Active / Archived tabs --}}
        <div style="display: flex; align-items: center; gap: 0; margin-right: 16px; margin-top: 4px;">
            <button
                wire:click="setCkTab('active')"
                type="button"
                style="
                    padding: 8px 20px;
                    font-size: 13px;
                    font-weight: 600;
                    border: 1px solid {{ $ckTab === 'active' ? '#059669' : '#e4e4e7' }};
                    background: {{ $ckTab === 'active' ? '#f0fdf4' : '#fff' }};
                    color: {{ $ckTab === 'active' ? '#059669' : '#71717a' }};
                    border-radius: 10px 0 0 10px;
                    cursor: pointer;
                    transition: all 0.2s;
                "
            >Active</button>
            <button
                wire:click="setCkTab('archived')"
                type="button"
                style="
                    padding: 8px 20px;
                    font-size: 13px;
                    font-weight: 600;
                    border: 1px solid {{ $ckTab === 'archived' ? '#059669' : '#e4e4e7' }};
                    border-left: none;
                    background: {{ $ckTab === 'archived' ? '#f0fdf4' : '#fff' }};
                    color: {{ $ckTab === 'archived' ? '#059669' : '#71717a' }};
                    border-radius: 0 10px 10px 0;
                    cursor: pointer;
                    transition: all 0.2s;
                "
            >Archived</button>
        </div>

        {{-- New Organisation button --}}
        <a
            href="{{ $createUrl }}"
            wire:navigate
            style="
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 14px 26px;
                background: linear-gradient(135deg, #006948 0%, #0f9361 45%, #27d384 100%);
                color: #fff;
                font-size: 14px;
                font-weight: 700;
                border-radius: 28px;
                text-decoration: none;
                box-shadow: 0 6px 20px rgba(5,150,105,0.35);
                transition: all 0.2s;
                white-space: nowrap;
            "
        >
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            New Organisation
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         2. FILTER BAR + TOTAL COUNTER
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    ">
        {{-- Filters --}}
        <div style="
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(228,228,231,0.5);
            border-radius: 16px;
            padding: 16px 24px;
            backdrop-filter: blur(20px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        ">
            {{-- Filter by Plan --}}
            <div style="flex: 1; max-width: 180px;">
                <label style="display: block; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #a1a1aa; margin-bottom: 6px;">FILTER BY PLAN</label>
                <select
                    wire:model.live="ckPlanFilter"
                    style="
                        width: 100%;
                        padding: 8px 12px;
                        border: 1px solid #e4e4e7;
                        border-radius: 10px;
                        font-size: 13px;
                        font-weight: 600;
                        color: #3f3f46;
                        background: #fff;
                        cursor: pointer;
                        outline: none;
                        appearance: auto;
                    "
                >
                    <option value="">All Plans</option>
                    <option value="free">Free / Community</option>
                    <option value="school">Standard Edu</option>
                    <option value="enterprise">Enterprise Global</option>
                </select>
            </div>

            {{-- Status --}}
            <div style="flex: 1; max-width: 160px;">
                <label style="display: block; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #a1a1aa; margin-bottom: 6px;">STATUS</label>
                <select
                    wire:model.live="ckStatusFilter"
                    style="
                        width: 100%;
                        padding: 8px 12px;
                        border: 1px solid #e4e4e7;
                        border-radius: 10px;
                        font-size: 13px;
                        font-weight: 600;
                        color: #3f3f46;
                        background: #fff;
                        cursor: pointer;
                        outline: none;
                        appearance: auto;
                    "
                >
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            {{-- Region --}}
            <div style="flex: 1; max-width: 160px;">
                <label style="display: block; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #a1a1aa; margin-bottom: 6px;">REGION</label>
                <select
                    wire:model.live="ckRegionFilter"
                    style="
                        width: 100%;
                        padding: 8px 12px;
                        border: 1px solid #e4e4e7;
                        border-radius: 10px;
                        font-size: 13px;
                        font-weight: 600;
                        color: #3f3f46;
                        background: #fff;
                        cursor: pointer;
                        outline: none;
                        appearance: auto;
                    "
                >
                    <option value="">Global</option>
                    <option value="west-africa">West Africa</option>
                    <option value="east-africa">East Africa</option>
                    <option value="southern-africa">Southern Africa</option>
                    <option value="central-africa">Central Africa</option>
                </select>
            </div>

            {{-- Reset Filters --}}
            <button
                wire:click="ckResetFilters"
                type="button"
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 10px 16px;
                    background: transparent;
                    border: none;
                    color: #059669;
                    font-size: 13px;
                    font-weight: 600;
                    cursor: pointer;
                    white-space: nowrap;
                    margin-top: 16px;
                "
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                Reset Filters
            </button>
        </div>

        {{-- Total Organisations counter --}}
        <div style="
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: 16px;
            padding: 16px 28px;
            text-align: center;
            min-width: 170px;
            box-shadow: 0 2px 8px rgba(16,185,129,0.1);
        ">
            <p style="font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.14em; color: #059669; margin: 0;">TOTAL ORGANISATIONS</p>
            <p style="
                font-family: 'Manrope', sans-serif;
                font-size: 36px;
                font-weight: 800;
                color: #059669;
                margin: 4px 0 0 0;
                letter-spacing: -0.02em;
            ">{{ number_format($totalOrganisations) }}</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         3. ORGANISATIONS TABLE
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        background: rgba(255,255,255,0.95);
        border: 1px solid rgba(228,228,231,0.5);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        backdrop-filter: blur(20px);
    ">
        {{-- Table header --}}
        <div style="
            display: grid;
            grid-template-columns: 2.5fr 1.3fr 0.8fr 0.8fr 1fr 1.2fr;
            gap: 8px;
            padding: 14px 24px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #a1a1aa;
            border-bottom: 1px solid #f4f4f5;
            background: #fafafa;
        ">
            <span>Organisation & Logo</span>
            <span>Plan Tier</span>
            <span style="text-align: center;">Users</span>
            <span style="text-align: center;">Children</span>
            <span>Status</span>
            <span style="text-align: right;">Administrative Actions</span>
        </div>

        {{-- Table rows --}}
        @forelse ($organisations as $org)
            @php
                // Plan badge configuration
                $planBadge = match ($org->plan) {
                    'enterprise' => [
                        'label' => 'ENTERPRISE GLOBAL',
                        'bg' => 'linear-gradient(135deg, #059669, #10b981)',
                        'color' => '#fff',
                    ],
                    'school' => [
                        'label' => 'STANDARD EDU',
                        'bg' => 'linear-gradient(135deg, #d97706, #f59e0b)',
                        'color' => '#fff',
                    ],
                    default => [
                        'label' => 'COMMUNITY',
                        'bg' => '#f4f4f5',
                        'color' => '#71717a',
                    ],
                };

                // Avatar gradient
                $avatarGradient = match ($org->plan) {
                    'enterprise' => 'linear-gradient(135deg, #059669, #10b981)',
                    'school' => 'linear-gradient(135deg, #d97706, #f59e0b)',
                    default => 'linear-gradient(135deg, #7c3aed, #a78bfa)',
                };

                // Generate a pseudo-ID
                $orgId = 'PK-' . str_pad($org->id * 1102 + 167, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(substr($org->slug, 0, 2));

                // Region mapping
                $regionNames = ['West Africa', 'East Africa', 'Southern Africa', 'Central Africa', 'Global'];
                $region = $regionNames[$org->id % count($regionNames)];
            @endphp
            <div
                style="
                    display: grid;
                    grid-template-columns: 2.5fr 1.3fr 0.8fr 0.8fr 1fr 1.2fr;
                    gap: 8px;
                    padding: 18px 24px;
                    align-items: center;
                    border-bottom: 1px solid #fafafa;
                    transition: background 0.15s;
                "
                class="ck-org-row-hover"
            >
                {{-- Organisation & Logo --}}
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="
                        width: 44px; height: 44px;
                        border-radius: 14px;
                        background: {{ $avatarGradient }};
                        display: flex; align-items: center; justify-content: center;
                        color: #fff;
                        font-size: 16px;
                        font-weight: 800;
                        flex-shrink: 0;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                        position: relative;
                    ">
                        {{ strtoupper(substr($org->name, 0, 2)) }}
                        @if ($org->plan === 'enterprise')
                            <span style="
                                position: absolute;
                                bottom: -2px; right: -2px;
                                width: 16px; height: 16px;
                                border-radius: 50%;
                                background: #059669;
                                border: 2px solid #fff;
                                display: flex; align-items: center; justify-content: center;
                                font-size: 8px; color: #fff;
                            ">✓</span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size: 14px; font-weight: 700; color: #18181b; margin: 0; line-height: 1.3;">{{ $org->name }}</p>
                        <p style="font-size: 11px; color: #a1a1aa; margin: 2px 0 0 0; font-weight: 500;">ID: {{ $orgId }}</p>
                    </div>
                </div>

                {{-- Plan Tier badge --}}
                <div>
                    <span style="
                        display: inline-block;
                        padding: 6px 14px;
                        border-radius: 8px;
                        font-size: 10px;
                        font-weight: 800;
                        text-transform: uppercase;
                        letter-spacing: 0.04em;
                        background: {{ $planBadge['bg'] }};
                        color: {{ $planBadge['color'] }};
                        white-space: nowrap;
                    ">{{ $planBadge['label'] }}</span>
                </div>

                {{-- Users --}}
                <div style="text-align: center;">
                    <span style="font-size: 15px; font-weight: 700; color: #18181b;">{{ number_format($org->users_count) }}</span>
                </div>

                {{-- Children --}}
                <div style="text-align: center;">
                    <span style="font-size: 15px; font-weight: 700; color: #18181b;">{{ number_format($org->child_profiles_count) }}</span>
                </div>

                {{-- Status --}}
                <div>
                    <span style="
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        font-size: 12px;
                        font-weight: 700;
                        color: {{ $org->is_active ? '#059669' : '#dc2626' }};
                    ">
                        <span style="
                            width: 8px; height: 8px;
                            border-radius: 50%;
                            background: {{ $org->is_active ? '#10b981' : '#ef4444' }};
                            display: inline-block;
                            {{ $org->is_active ? 'box-shadow: 0 0 6px rgba(16,185,129,0.5);' : 'box-shadow: 0 0 6px rgba(239,68,68,0.4);' }}
                        "></span>
                        {{ $org->is_active ? 'ACTIVE' : 'SUSPENDED' }}
                    </span>
                </div>

                {{-- Administrative Actions --}}
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <a
                        href="{{ \App\Filament\Resources\OrganisationResource::getUrl('edit', ['record' => $org]) }}"
                        wire:navigate
                        style="
                            display: inline-flex;
                            align-items: center;
                            gap: 4px;
                            padding: 7px 14px;
                            border: 1px solid #e4e4e7;
                            border-radius: 8px;
                            background: #fff;
                            color: #3f3f46;
                            font-size: 11px;
                            font-weight: 600;
                            text-decoration: none;
                            cursor: pointer;
                            transition: all 0.15s;
                        "
                        class="ck-action-btn"
                    >
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </a>

                    @if ($org->is_active)
                        <button
                            wire:click="suspendOrg({{ $org->id }})"
                            wire:confirm="Are you sure you want to suspend {{ $org->name }}?"
                            type="button"
                            style="
                                display: inline-flex;
                                align-items: center;
                                gap: 4px;
                                padding: 7px 14px;
                                border: 1px solid #fecaca;
                                border-radius: 8px;
                                background: #fef2f2;
                                color: #dc2626;
                                font-size: 11px;
                                font-weight: 600;
                                cursor: pointer;
                                transition: all 0.15s;
                            "
                            class="ck-action-btn"
                        >
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="10" y1="15" x2="10" y2="9"/><line x1="14" y1="15" x2="14" y2="9"/></svg>
                            Suspend
                        </button>
                    @else
                        <button
                            wire:click="activateOrg({{ $org->id }})"
                            wire:confirm="Reactivate {{ $org->name }}?"
                            type="button"
                            style="
                                display: inline-flex;
                                align-items: center;
                                gap: 4px;
                                padding: 7px 14px;
                                border: 1px solid #bbf7d0;
                                border-radius: 8px;
                                background: #f0fdf4;
                                color: #059669;
                                font-size: 11px;
                                font-weight: 600;
                                cursor: pointer;
                                transition: all 0.15s;
                            "
                            class="ck-action-btn"
                        >
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            Activate
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: 60px 20px; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d4d4d8" stroke-width="1.5" style="margin: 0 auto 16px;"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                <p style="font-size: 16px; font-weight: 700; color: #71717a; margin: 0;">No organisations found</p>
                <p style="font-size: 13px; color: #a1a1aa; margin: 6px 0 0 0;">Try adjusting your filters or create a new organisation.</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         4. FOOTER COUNT
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 20px;
        padding: 0 4px;
    ">
        <p style="font-size: 13px; color: #71717a; font-weight: 500; margin: 0;">
            Showing
            <span style="font-weight: 700; color: #18181b;">1-{{ count($organisations) }}</span>
            of
            <span style="font-weight: 700; color: #18181b;">{{ number_format($totalOrganisations) }}</span>
            organisations
        </p>

        <div style="display: flex; align-items: center; gap: 4px;">
            <span style="padding: 8px 14px; font-size: 13px; font-weight: 500; color: #d4d4d8; cursor: default;">Previous</span>
            <button type="button" style="
                width: 36px; height: 36px;
                display: flex; align-items: center; justify-content: center;
                border-radius: 10px; font-size: 13px; font-weight: 700;
                border: none; cursor: pointer;
                background: linear-gradient(135deg, #059669, #10b981);
                color: #fff;
                box-shadow: 0 2px 8px rgba(16,185,129,0.3);
            ">1</button>
            <span style="padding: 8px 14px; font-size: 13px; font-weight: 500; color: #d4d4d8; cursor: default;">Next</span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         FAB — Quick Action Button (bottom-right corner)
    ═══════════════════════════════════════════════════════════════ --}}
    <div style="
        position: fixed;
        bottom: 32px;
        right: 32px;
        z-index: 50;
    ">
        <a
            href="{{ $createUrl }}"
            wire:navigate
            style="
                width: 56px; height: 56px;
                border-radius: 50%;
                background: linear-gradient(135deg, #006948 0%, #0f9361 45%, #27d384 100%);
                display: flex; align-items: center; justify-content: center;
                box-shadow: 0 8px 24px rgba(5,150,105,0.4);
                transition: transform 0.2s, box-shadow 0.2s;
                text-decoration: none;
            "
            class="ck-fab-hover"
        >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     HOVER STYLES
═══════════════════════════════════════════════════════════════ --}}
<style>
    .ck-org-row-hover:hover {
        background: rgba(240,253,244,0.4) !important;
    }
    .ck-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .ck-fab-hover:hover {
        transform: scale(1.08);
        box-shadow: 0 12px 32px rgba(5,150,105,0.5);
    }
    /* Remove default Filament page header to use our custom one */
    .fi-page-header {
        display: none !important;
    }
</style>
</x-filament-panels::page>
