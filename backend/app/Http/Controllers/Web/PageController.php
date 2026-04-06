<?php

namespace App\Http\Controllers\Web;

use App\Models\ChildProfile;
use App\Models\Comic;
use App\Models\Tribe;
use App\Models\User;
use App\Models\ProgressEvent;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController
{
    /**
     * Show welcome/landing page
     */
    public function welcome(): View
    {
        $tribes = Tribe::active()->take(6)->get();
        return view('welcome', compact('tribes'));
    }

    /**
     * Show about page
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * Show all tribes (public)
     */
    public function tribes(): View
    {
        $tribes = Tribe::active()->paginate(12);
        return view('tribes.index', compact('tribes'));
    }

    /**
     * Show single tribe with its comics
     */
    public function showTribe(int $id): View
    {
        $tribe = Tribe::findOrFail($id);
        $comics = $tribe->comics()->published()->paginate(12);
        return view('tribes.show', compact('tribe', 'comics'));
    }

    /**
     * Show parent dashboard
     */
    public function parentDashboard(): View
    {
        $children = auth()->user()->childProfiles()->with('progressEvents')->get();
        
        $dashboardData = [
            'children' => $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'avatar' => $child->avatar ?? '👶',
                    'stories_completed' => $child->progressEvents()
                        ->where('event_type', 'story_completed')
                        ->distinct('comic_id')
                        ->count(),
                    'badges_earned' => $child->progressEvents()
                        ->where('event_type', 'badge_earned')
                        ->count(),
                    'total_time_minutes' => ceil($child->progressEvents()
                        ->whereNotNull('duration_seconds')
                        ->sum('duration_seconds') / 60),
                    'weekly_progress' => $child->progressEvents()
                        ->where('recorded_at', '>=', now()->subDays(7))
                        ->count(),
                    'last_active' => $child->progressEvents()
                        ->latest('recorded_at')
                        ->value('recorded_at') ?? 'Never',
                ];
            }),
            'stats' => [
                'total_children' => $children->count(),
                'total_stories' => $children->sum(function ($child) {
                    return $child->progressEvents()
                        ->where('event_type', 'story_completed')
                        ->distinct('comic_id')
                        ->count();
                }),
                'total_badges' => $children->sum(function ($child) {
                    return $child->progressEvents()
                        ->where('event_type', 'badge_earned')
                        ->count();
                }),
            ],
            'recent_activity' => ProgressEvent::whereIn('child_id', $children->pluck('id'))
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($event) {
                    $child = $event->child;
                    return [
                        'child_name' => $child->name,
                        'event_type' => $event->event_type,
                        'description' => $this->getEventDescription($event),
                        'time' => $event->recorded_at?->diffForHumans() ?? 'Recently',
                    ];
                }),
        ];

        return view('parent.dashboard', $dashboardData)->layout('layouts.dashboard', [
            'header_title' => 'Welcome back, ' . auth()->user()->name,
            'header_subtitle' => "Track your children's learning journey",
        ]);
    }

    /**
     * Get human-friendly event description
     */
    private function getEventDescription(ProgressEvent $event): string
    {
        return match ($event->event_type) {
            'story_started' => '📖 Started a story',
            'story_completed' => '✅ Completed a story',
            'badge_earned' => '⭐ Earned a badge',
            'vocab_learned' => '📚 Learned vocabulary',
            'exercise_completed' => '🎯 Completed exercise',
            default => 'Activity recorded',
        };
    }

    /**
     * Show parent's children list
     */
    public function parentChildren(): View
    {
        $children = auth()->user()->childProfiles()->with(['progressEvents'])->get();
        
        return view('parent.children', [
            'children' => $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'age' => $child->getAge(),
                    'avatar_color' => $child->avatar_color,
                    'stories_completed' => $child->progressEvents()
                        ->where('event_type', 'story_completed')
                        ->count(),
                    'badges_earned' => $child->progressEvents()
                        ->where('event_type', 'badge_earned')
                        ->count(),
                    'last_active' => $child->progressEvents()->latest()->first()?->recorded_at,
                ];
            }),
        ]);
    }

    /**
     * Show child progress details
     */
    public function childProgress(int $id): View
    {
        $child = ChildProfile::findOrFail($id);

        // Verify parent owns this child
        if ($child->parent_user_id !== auth()->id()) {
            throw new AuthorizationException();
        }

        $events = $child->progressEvents()
            ->latest()
            ->paginate(20);

        $stats = [
            'stories_completed' => $child->progressEvents()
                ->where('event_type', 'story_completed')
                ->count(),
            'vocab_learned' => $child->progressEvents()
                ->where('event_type', 'vocab_learned')
                ->count(),
            'badges_earned' => $child->progressEvents()
                ->where('event_type', 'badge_earned')
                ->count(),
            'total_time_minutes' => (int) ($child->progressEvents()
                ->sum('duration_seconds') / 60),
        ];

        return view('parent.child-progress', [
            'child' => $child,
            'events' => $events,
            'stats' => $stats,
        ]);
    }

    /**
     * Show teacher dashboard
     */
    public function teacherDashboard(): View
    {
        $classrooms = auth()->user()->lessonPlans()
            ->where('status', '!=', 'completed')
            ->with('childProfiles')
            ->get();

        $allAssignedChildren = ChildProfile::whereHas('progressEvents', function ($q) {
            $q->whereHas('child.parent.lessonPlans', function ($q2) {
                $q2->where('teacher_id', auth()->id());
            });
        })->get();

        $stats = [
            'pupils_today' => $allAssignedChildren->count(),
            'stories_assigned' => ProgressEvent::whereIn('child_id', $allAssignedChildren->pluck('id'))
                ->where('event_type', 'story_completed')
                ->distinct('comic_id')
                ->count(),
            'badges_awarded' => ProgressEvent::whereIn('child_id', $allAssignedChildren->pluck('id'))
                ->where('event_type', 'badge_earned')
                ->count(),
            'total_time_minutes' => ceil(ProgressEvent::whereIn('child_id', $allAssignedChildren->pluck('id'))
                ->whereNotNull('duration_seconds')
                ->sum('duration_seconds') / 60),
        ];

        return view('teacher.dashboard', compact('stats', 'classrooms'))->layout('layouts.dashboard', [
            'header_title' => 'Teacher Dashboard',
            'header_subtitle' => 'Classroom management & progress tracking',
        ]);
    }

    /**
     * Show teacher roster/class
     */
    public function teacherRoster(): View
    {
        return view('teacher.roster');
    }
}
