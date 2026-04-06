@extends('layouts.dashboard')

@section('header_title', 'Welcome Back, ' . Auth::user()->name)

@section('content')
<div class="space-y-8">
    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card icon="👶" label="Active Children" :value="$stats['total_children']" />
        <x-stat-card icon="📖" label="Stories Completed" :value="$stats['total_stories']" />
        <x-stat-card icon="⭐" label="Badges Earned" :value="$stats['total_badges']" />
    </div>

    {{-- Children Overview --}}
    <x-card title="Your Children" subtitle="Track each child's learning progress">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($children as $child)
                <div class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ $child['name'] }}</h3>
                            <p class="text-sm text-slate-600">Last active: {{ $child['last_active'] }}</p>
                        </div>
                        <div class="text-4xl">{{ $child['avatar'] }}</div>
                    </div>

                    {{-- Progress Ring --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-slate-700">Weekly Progress</span>
                            <span class="text-sm font-bold text-indigo-600">{{ $child['weekly_progress'] }} activities</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min($child['weekly_progress'] * 10, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b border-slate-200">
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $child['stories_completed'] }}</p>
                            <p class="text-xs text-slate-600">Stories</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $child['badges_earned'] }}</p>
                            <p class="text-xs text-slate-600">Badges</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $child['total_time_minutes'] }}m</p>
                            <p class="text-xs text-slate-600">Time</p>
                        </div>
                    </div>

                    {{-- Badges Display --}}
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Progress</p>
                        <div class="flex flex-wrap gap-2">
                            @if($child['stories_completed'] >= 5)
                                <x-badge icon="📖" type="success">{{ $child['stories_completed'] }} Stories</x-badge>
                            @endif
                            @if($child['badges_earned'] > 0)
                                <x-badge icon="⭐" type="primary">{{ $child['badges_earned'] }} Badges</x-badge>
                            @endif
                            @if($child['weekly_progress'] >= 7)
                                <x-badge icon="🔥" type="warning">Active</x-badge>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <x-button href="{{ route('parent.child.progress', ['id' => $child['id']]) }}" variant="primary" size="sm" class="flex-1">
                            <span>📊 View Details</span>
                        </x-button>
                        <x-button href="#" variant="secondary" size="sm" class="flex-1">
                            <span>✏️ Edit</span>
                        </x-button>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12">
                    <p class="text-slate-600 mb-4">No children added yet</p>
                    <x-button href="#" variant="primary">Add Your First Child</x-button>
                </div>
            @endforelse
        </div>
    </x-card>

    {{-- Recent Activity --}}
    <x-card title="Recent Activity" subtitle="What your children are up to">
        <div class="space-y-4">
            @forelse($recent_activity as $activity)
                <div class="flex items-start gap-4 pb-4 border-b border-slate-200 last:border-0">
                    <div class="text-2xl">📝</div>
                    <div class="flex-1">
                        <p class="font-medium text-slate-900">{{ $activity['child_name'] }} <span class="font-normal text-slate-600">{{ $activity['description'] }}</span></p>
                        <p class="text-sm text-slate-500">{{ $activity['time'] }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-600 py-8">No recent activity yet</p>
            @endforelse
        </div>
    </x-card>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <x-button href="#" variant="primary" size="md" class="w-full justify-center">
            <span>⬇️ Download Content</span>
        </x-button>
        <x-button href="#" variant="outline" size="md" class="w-full justify-center">
            <span>📚 Parent Guide</span>
        </x-button>
        <x-button href="#" variant="secondary" size="md" class="w-full justify-center">
            <span>⚙️ Preferences</span>
        </x-button>
        <x-button href="#" variant="secondary" size="md" class="w-full justify-center">
            <span>❓ Support</span>
        </x-button>
    </div>
</div>

@endsection
