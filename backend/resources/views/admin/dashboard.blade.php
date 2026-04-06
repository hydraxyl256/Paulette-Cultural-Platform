@extends('layouts.dashboard')

@section('header_title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Top KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card icon="👶" label="Active Children" value="2,847" trend="+12% this week" />
        <x-stat-card icon="🏢" label="Organisations" value="34" trend="+3 this month" />
        <x-stat-card icon="📖" label="Published Stories" value="183" trend="65 tribes" />
        <x-stat-card icon="⭐" label="Badges Earned" value="9,240" trend="Last 7 days" />
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        {{-- <x-button href="{{ route('filament.admin.resources.organisations.create') }}" variant="primary" size="md" class="w-full justify-center">
            <span>🏢 New Organisation</span>
        </x-button>
        <x-button href="{{ route('filament.admin.resources.comics.create') }}" variant="primary" size="md" class="w-full justify-center">
            <span>📖 Add Story</span>
        </x-button>
        <x-button href="{{ route('filament.admin.resources.age-profiles.index') }}" variant="outline" size="md" class="w-full justify-center">
            <span>👶 Age Profiles</span>
        </x-button>
        <x-button href="{{ route('filament.admin.resources.audit-logs.index') }}" variant="secondary" size="md" class="w-full justify-center">
            <span>📋 Audit Logs</span>
        </x-button> --}}
    </div>

    {{-- Organisations Table --}}
    <x-card title="Active Organisations" subtitle="All registered clients">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Organisation</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Plan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Children</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Stories</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-slate-900">Naluwooza Creative Space</p>
                                <p class="text-xs text-slate-600">Creative Org</p>
                            </div>
                        </td>
                        <td class="px-6 py-4"><x-badge icon="💎" type="primary">Enterprise</x-badge></td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">428</span></td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">48</span></td>
                        <td class="px-6 py-4"><x-badge icon="●" type="success">Active</x-badge></td>
                        <td class="px-6 py-4 space-x-2">
                            <x-button variant="secondary" size="sm" href="#">Edit</x-button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-slate-900">Uganda Schools Pilot</p>
                                <p class="text-xs text-slate-600">School Network</p>
                            </div>
                        </td>
                        <td class="px-6 py-4"><x-badge icon="🎓" type="primary">School</x-badge></td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">1,203</span></td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">62</span></td>
                        <td class="px-6 py-4"><x-badge icon="●" type="success">Active</x-badge></td>
                        <td class="px-6 py-4 space-x-2">
                            <x-button variant="secondary" size="sm" href="#">Edit</x-button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-slate-900">Default Organisation</p>
                                <p class="text-xs text-slate-600">Sandbox</p>
                            </div>
                        </td>
                        <td class="px-6 py-4"><x-badge icon="🆓" type="slate">Free</x-badge></td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">216</span></td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">25</span></td>
                        <td class="px-6 py-4"><x-badge icon="●" type="success">Active</x-badge></td>
                        <td class="px-6 py-4 space-x-2">
                            <x-button variant="secondary" size="sm" href="#">Edit</x-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- System Modules Control --}}
    <x-card title="Feature Modules" subtitle="Enable/disable features globally">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-slate-900">📖 Comics</h4>
                        <p class="text-xs text-slate-600">Story panel viewer</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-indigo-600 rounded cursor-pointer" />
                </div>
                <p class="text-xs text-slate-700">183 stories available</p>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-slate-900">🎵 Songs & Audio</h4>
                        <p class="text-xs text-slate-600">Music + pronunciation</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-indigo-600 rounded cursor-pointer" />
                </div>
                <p class="text-xs text-slate-700">156 audio files</p>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-slate-900">🃏 Flashcards</h4>
                        <p class="text-xs text-slate-600">Vocabulary learning</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-indigo-600 rounded cursor-pointer" />
                </div>
                <p class="text-xs text-slate-700">847 card sets</p>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-slate-900">📡 Offline Sync</h4>
                        <p class="text-xs text-slate-600">Bundle downloads</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-indigo-600 rounded cursor-pointer" />
                </div>
                <p class="text-xs text-slate-700">42,834 synced</p>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-slate-900">🖥️ Kiosk Mode</h4>
                        <p class="text-xs text-slate-600">Classroom display</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-indigo-600 rounded cursor-pointer" />
                </div>
                <p class="text-xs text-slate-700">18 active kiosks</p>
            </div>

            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-slate-900">🎨 Custom Themes</h4>
                        <p class="text-xs text-slate-600">Brand customization</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-indigo-600 rounded cursor-pointer" />
                </div>
                <p class="text-xs text-slate-700">4 themes active</p>
            </div>
        </div>
    </x-card>

    {{-- System Health & Insights --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-card title="Platform Health" subtitle="System metrics">
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700">API Uptime</span>
                        <span class="text-sm font-bold text-green-600">99.98%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 99.98%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700">Response Time</span>
                        <span class="text-sm font-bold text-slate-900">156ms</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700">Queue Size</span>
                        <span class="text-sm font-bold text-amber-600">234 jobs</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-amber-600 h-2 rounded-full" style="width: 23%"></div>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Top Tribes" subtitle="by engagement">
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-3 border-b border-slate-200">
                    <p class="text-sm font-medium text-slate-900">🍎 Buganda</p>
                    <x-badge type="success">487</x-badge>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-200">
                    <p class="text-sm font-medium text-slate-900">🦅 Acholi</p>
                    <x-badge type="primary">342</x-badge>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-200">
                    <p class="text-sm font-medium text-slate-900">🌊 Basoga</p>
                    <x-badge type="primary">298</x-badge>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-slate-900">🌾 Iteso</p>
                    <x-badge type="warning">156</x-badge>
                </div>
            </div>
        </x-card>

        <x-card title="Recent Activity" subtitle="Last 24 hours">
            <div class="space-y-3 text-sm">
                <div class="flex items-start space-x-2">
                    <span>✅</span>
                    <div>
                        <p class="font-medium text-slate-900">12 new organisations registered</p>
                        <p class="text-xs text-slate-600">2 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <span>📖</span>
                    <div>
                        <p class="font-medium text-slate-900">3 stories published</p>
                        <p class="text-xs text-slate-600">4 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <span>⚠️</span>
                    <div>
                        <p class="font-medium text-slate-900">API rate limit exceeded (1 org)</p>
                        <p class="text-xs text-slate-600">6 hours ago</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
