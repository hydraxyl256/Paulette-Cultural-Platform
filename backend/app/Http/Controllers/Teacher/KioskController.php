<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Tribe;
use App\Models\Comic;
use App\Models\ProgressEvent;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Teacher Kiosk Mode Controller
 * 
 * Kiosk mode is a simplified UI for classroom settings where:
 * - Kids interact with tablets/touchscreens
 * - Teachers monitor progress
 * - Content is full-screen, distraction-free
 * - Automatic rewards/achievements on completion
 */
class KioskController
{
    /**
     * Show kiosk home screen (tribe selector)
     */
    public function index(): View
    {
        $tribes = Tribe::active()->with(['comics'])->get();
        
        return view('teacher.kiosk.index', [
            'tribes' => $tribes,
            'isKiosk' => true,
        ]);
    }

    /**
     * Show tribe stories in kiosk mode
     */
    public function showTribe(int $id): View
    {
        $tribe = Tribe::with(['comics' => function ($q) {
            $q->where('status', 'published');
        }])->findOrFail($id);

        return view('teacher.kiosk.tribe', [
            'tribe' => $tribe,
            'comics' => $tribe->comics,
            'isKiosk' => true,
        ]);
    }

    /**
     * Show comic in fullscreen kiosk viewer
     */
    public function viewer(int $id): View
    {
        $comic = Comic::where('status', 'published')->findOrFail($id);
        $panels = $comic->panels()->orderBy('panel_number')->get();

        return view('teacher.kiosk.viewer', [
            'comic' => $comic,
            'panels' => $panels,
            'isKiosk' => true,
        ]);
    }

    /**
     * Record completion event (AJAX)
     */
    public function recordCompletion(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'comic_id' => 'required|exists:comics,id',
            'child_name' => 'required|string|max:255', // Kiosk doesn't require login
            'duration_seconds' => 'nullable|integer|min:0',
        ]);

        // In kiosk mode, create temporary progress event
        // (actual child linking happens in admin later)
        $event = ProgressEvent::create([
            'child_id' => 0, // Placeholder for kiosk mode
            'comic_id' => $validated['comic_id'],
            'event_type' => 'story_completed',
            'duration_seconds' => $validated['duration_seconds'],
            'metadata' => [
                'child_name' => $validated['child_name'],
                'kiosk_mode' => true,
            ],
            'recorded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Story completed! Great job!',
            'event_id' => $event->id,
        ]);
    }

    /**
     * Dashboard for monitoring kiosk sessions
     */
    public function monitor(): View
    {
        $recentCompletions = ProgressEvent::where('metadata->kiosk_mode', true)
            ->latest()
            ->take(20)
            ->get();

        $todayStats = [
            'stories_completed' => ProgressEvent::where('metadata->kiosk_mode', true)
                ->whereDate('recorded_at', today())
                ->count(),
            'unique_children' => ProgressEvent::where('metadata->kiosk_mode', true)
                ->whereDate('recorded_at', today())
                ->distinct('metadata->child_name')
                ->count(),
        ];

        return view('teacher.kiosk.monitor', [
            'recentCompletions' => $recentCompletions,
            'todayStats' => $todayStats,
        ]);
    }
}
