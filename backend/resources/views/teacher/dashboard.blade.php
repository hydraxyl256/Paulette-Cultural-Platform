@extends('layouts.dashboard')

@section('header_title', 'Teacher Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Top Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card icon="👥" label="Pupils Today" value="24" />
        <x-stat-card icon="📖" label="Stories Completed" value="156" trend="+12 this week" />
        <x-stat-card icon="⭐" label="Badges Awarded" value="56" trend="+8 this week" />
        <x-stat-card icon="⏱️" label="Total Time" value="2,340m" trend="per week" />
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <x-button href="#" variant="primary" size="md" class="w-full justify-center">
            <span>📅 Lesson Plan</span>
        </x-button>
        <x-button href="{{ route('teacher.kiosk') }}" variant="primary" size="md" class="w-full justify-center">
            <span>🖥️ Kiosk Mode</span>
        </x-button>
        <x-button href="#" variant="outline" size="md" class="w-full justify-center">
            <span>📊 Export Report</span>
        </x-button>
        <x-button href="#" variant="secondary" size="md" class="w-full justify-center">
            <span>⚙️ Settings</span>
        </x-button>
    </div>

    {{-- Class Completion Chart --}}
    <x-card title="Weekly Story Completions" subtitle="Track class progress this week">
        <div class="h-64 flex items-center justify-center bg-slate-50 rounded-lg border border-slate-200">
            <div class="text-center">
                <p class="text-slate-600 mb-2">📊 Chart.js Chart</p>
                <p class="text-sm text-slate-500">Install Chart.js to display interactive charts</p>
            </div>
        </div>
    </x-card>

    {{-- Class Progress Table --}}
    <x-card title="Class Progress This Week" subtitle="Individual pupil performance">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Pupil Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Stories</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Badges</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Time (min)</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-semibold">A</div>
                                <span class="font-medium text-slate-900">Amara</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">8</span></td>
                        <td class="px-6 py-4"><x-badge icon="⭐" type="success">3 Badges</x-badge></td>
                        <td class="px-6 py-4 text-slate-700">125</td>
                        <td class="px-6 py-4"><x-badge icon="✓" type="success">On Track</x-badge></td>
                        <td class="px-6 py-4"><x-button variant="secondary" size="sm" href="#">View</x-button></td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-sm font-semibold">K</div>
                                <span class="font-medium text-slate-900">Kiprotich</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">6</span></td>
                        <td class="px-6 py-4"><x-badge icon="⭐" type="primary">2 Badges</x-badge></td>
                        <td class="px-6 py-4 text-slate-700">98</td>
                        <td class="px-6 py-4"><x-badge icon="⚠️" type="warning">Needs Help</x-badge></td>
                        <td class="px-6 py-4"><x-button variant="secondary" size="sm" href="#">View</x-button></td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-sm font-semibold">N</div>
                                <span class="font-medium text-slate-900">Naluwooza</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">12</span></td>
                        <td class="px-6 py-4"><x-badge icon="⭐" type="primary">5 Badges</x-badge></td>
                        <td class="px-6 py-4 text-slate-700">180</td>
                        <td class="px-6 py-4"><x-badge icon="🌟" type="success">Excellent</x-badge></td>
                        <td class="px-6 py-4"><x-button variant="secondary" size="sm" href="#">View</x-button></td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-sm font-semibold">J</div>
                                <span class="font-medium text-slate-900">John</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">3</span></td>
                        <td class="px-6 py-4"><x-badge icon="📌" type="slate">0 Badges</x-badge></td>
                        <td class="px-6 py-4 text-slate-700">45</td>
                        <td class="px-6 py-4"><x-badge icon="❌" type="danger">Inactive</x-badge></td>
                        <td class="px-6 py-4"><x-button variant="secondary" size="sm" href="#">View</x-button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Learning Trends --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card title="Most Popular Stories" subtitle="Among this week's completions">
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 hover:bg-slate-50 -mx-6 px-6 py-3 transition">
                    <div class="flex-1">
                        <p class="font-medium text-slate-900">Buganda Heritage</p>
                        <p class="text-xs text-slate-600">12 completions</p>
                    </div>
                    <div class="w-16 bg-slate-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 hover:bg-slate-50 -mx-6 px-6 py-3 transition">
                    <div class="flex-1">
                        <p class="font-medium text-slate-900">Acholi Wisdom</p>
                        <p class="text-xs text-slate-600">10 completions</p>
                    </div>
                    <div class="w-16 bg-slate-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 83%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between hover:bg-slate-50 -mx-6 px-6 py-3 transition">
                    <div class="flex-1">
                        <p class="font-medium text-slate-900">Basoga Tales</p>
                        <p class="text-xs text-slate-600">8 completions</p>
                    </div>
                    <div class="w-16 bg-slate-200 rounded-full h-2">
                        <div class="bg-amber-600 h-2 rounded-full" style="width: 67%"></div>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Engagement Insight" subtitle="Weekly trends">
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700">Avg. Daily Active</span>
                        <span class="text-sm font-bold text-slate-900">22 pupils</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: 92%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700">Completion Rate</span>
                        <span class="text-sm font-bold text-slate-900">87%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 87%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700">Badge Earn Rate</span>
                        <span class="text-sm font-bold text-slate-900">3.2 avg/pupil</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-amber-600 h-2 rounded-full" style="width: 64%"></div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
