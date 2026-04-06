@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Teacher Dashboard</h1>
        <p class="text-gray-600">Classroom management · Lesson planner · Progress tracking</p>
    </div>

    {{-- Quick Access --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-indigo-600">
            <h3 class="text-gray-600 text-sm font-medium">Active Pupils Today</h3>
            <p class="text-3xl font-bold text-indigo-600">24</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-600">
            <h3 class="text-gray-600 text-sm font-medium">Completion Rate</h3>
            <p class="text-3xl font-bold text-green-600">78%</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-purple-600">
            <h3 class="text-gray-600 text-sm font-medium">Badges This Week</h3>
            <p class="text-3xl font-bold text-purple-600">56</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-4">
        <a href="/teacher/lesson-plan/new" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            📅 Create Lesson Plan
        </a>
        
        <a href="/teacher/class/roster" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            👥 View Class Roster
        </a>
        
        <a href="/teacher/reports/export" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
            📊 Export Report
        </a>
        
        <a href="/teacher/kiosk" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            🖥️ Launch Kiosk Mode
        </a>
    </div>

    {{-- Weekly Activity Chart --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Weekly Story Completions</h2>
        
        <div class="overflow-x-auto">
            <div style="width: 100%; height: 300px;">
                {{-- Chart.js would render here --}}
                <p class="text-gray-600">Chart placeholder - use Chart.js library</p>
            </div>
        </div>
    </div>

    {{-- Class Progress --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Class Progress This Week</h2>
        </div>
        
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Pupil Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Stories</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Badges</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Time (min)</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4">Amara</td>
                    <td class="px-6 py-4">8</td>
                    <td class="px-6 py-4">3</td>
                    <td class="px-6 py-4">125</td>
                    <td class="px-6 py-4"><span class="text-green-600">✓ On track</span></td>
                </tr>
                <tr>
                    <td class="px-6 py-4">Kiprotich</td>
                    <td class="px-6 py-4">6</td>
                    <td class="px-6 py-4">2</td>
                    <td class="px-6 py-4">98</td>
                    <td class="px-6 py-4"><span class="text-yellow-600">⚠ Needs attention</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
