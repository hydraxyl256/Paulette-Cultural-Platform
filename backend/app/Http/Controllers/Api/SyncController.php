<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SyncOfflineEventsRequest;
use App\Models\ChildProfile;
use App\Models\ProgressEvent;
use App\Models\SyncEvent;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncController extends Controller
{
    /**
     * POST /api/v1/sync
     * 
     * Drain Expo offline sync queue — batch process up to 100 events.
     * Fully idempotent: duplicate events (same idempotency_key) are skipped.
     * Awards badges based on progress thresholds.
     * 
     * Request:
     * {
     *   "events": [
     *     {
     *       "child_id": 1,
     *       "event_type": "story_completed",
     *       "tribe_id": 1,
     *       "comic_id": 5,
     *       "panel_number": 3,
     *       "duration_seconds": 180,
     *       "score": 85,
     *       "metadata": {"difficulty": "easy"},
     *       "recorded_at": "2026-04-01 14:30:00",
     *       "idempotency_key": "mobile-sync-abc123-def456"
     *     }
     *   ]
     * }
     * 
     * Response: 200 OK
     * {
     *   "message": "Sync completed successfully",
     *   "events_processed": 5,
     *   "events_skipped": 0,
     *   "badges_awarded": ["explorer_5"],
     *   "events": [...]
     * }
     */
    public function sync(SyncOfflineEventsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userId = auth()->id();
        $processedEvents = [];
        $skippedEvents = [];
        $badgesAwarded = [];

        try {
            DB::beginTransaction();

            foreach ($validated['events'] as $eventData) {
                try {
                    $result = $this->processEvent($eventData, $userId);
                    
                    if ($result['status'] === 'processed') {
                        $processedEvents[] = $result['event'];
                    } elseif ($result['status'] === 'skipped') {
                        $skippedEvents[] = $result['reason'];
                    }
                } catch (\Exception $e) {
                    // Log individual event failures but continue processing batch
                    \Log::warning('Sync event processing failed', [
                        'event' => $eventData,
                        'error' => $e->getMessage(),
                    ]);
                    $skippedEvents[] = "Event processing failed: {$e->getMessage()}";
                }
            }

            // Award badges based on progress
            $badgesAwarded = $this->awardBadgesForChildren(
                collect($processedEvents)->pluck('child_id')->unique()
            );

            // Create sync event record for audit
            SyncEvent::create([
                'user_id' => $userId,
                'events_processed' => count($processedEvents),
                'events_skipped' => count($skippedEvents),
                'payload' => [
                    'event_types' => collect($validated['events'])->pluck('event_type')->unique(),
                    'children_count' => collect($validated['events'])->pluck('child_id')->unique()->count(),
                ],
            ]);

            // Log sync activity
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'sync.events',
                'resource_type' => 'ProgressEvent',
                'changes' => [
                    'processed' => count($processedEvents),
                    'skipped' => count($skippedEvents),
                    'badges' => $badgesAwarded,
                ],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Sync completed successfully',
                'events_processed' => count($processedEvents),
                'events_skipped' => count($skippedEvents),
                'badges_awarded' => $badgesAwarded,
                'events' => $processedEvents,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sync transaction failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Sync failed: ' . $e->getMessage(),
                'events_processed' => 0,
                'events' => [],
            ], 500);
        }
    }

    /**
     * Process a single offline event
     */
    private function processEvent(array $eventData, int $userId): array
    {
        // Verify child ownership (parent can only sync own children)
        $child = ChildProfile::find($eventData['child_id']);
        if (!$child || $child->parent_user_id !== $userId) {
            return [
                'status' => 'skipped',
                'reason' => "Unauthorized: child {$eventData['child_id']} not owned by user",
            ];
        }

        // Generate idempotency key if not provided
        $idempotencyKey = $eventData['idempotency_key'] 
            ?? Str::uuid()->toString();

        // Check if event already processed (idempotency)
        $existing = ProgressEvent::where('idempotency_key', $idempotencyKey)->first();
        if ($existing) {
            return [
                'status' => 'skipped',
                'reason' => "Duplicate: event already processed (key: {$idempotencyKey})",
                'event' => $existing,
            ];
        }

        // Validate event_type is allowed
        $allowedTypes = [
            'story_started',
            'story_completed',
            'panel_viewed',
            'vocab_learned',
            'badge_earned',
            'exercise_completed',
        ];
        
        if (!in_array($eventData['event_type'], $allowedTypes)) {
            return [
                'status' => 'skipped',
                'reason' => "Invalid event_type: {$eventData['event_type']}",
            ];
        }

        // Create progress event
        $progressEvent = ProgressEvent::create([
            'child_id' => $eventData['child_id'],
            'event_type' => $eventData['event_type'],
            'tribe_id' => $eventData['tribe_id'] ?? null,
            'comic_id' => $eventData['comic_id'] ?? null,
            'panel_number' => $eventData['panel_number'] ?? null,
            'duration_seconds' => $eventData['duration_seconds'] ?? null,
            'score' => $eventData['score'] ?? null,
            'idempotency_key' => $idempotencyKey,
            'metadata' => $eventData['metadata'] ?? [],
            'recorded_at' => $eventData['recorded_at'] ?? now(),
            'synced_at' => now(),
        ]);

        // Log event creation
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'progress.event.created',
            'resource_type' => 'ProgressEvent',
            'resource_id' => $progressEvent->id,
            'changes' => [
                'event_type' => $progressEvent->event_type,
                'child_id' => $progressEvent->child_id,
            ],
        ]);

        return [
            'status' => 'processed',
            'event' => $progressEvent,
        ];
    }

    /**
     * Award badges to children based on milestone achievement
     */
    private function awardBadgesForChildren(Collection $childIds): array
    {
        $badgesAwarded = [];

        foreach ($childIds as $childId) {
            $child = ChildProfile::find($childId);
            if (!$child) continue;

            // Story Completion Milestones
            $storiesCompleted = $child->progressEvents()
                ->where('event_type', 'story_completed')
                ->count();

            // 5 Stories: Explorer Badge
            if ($storiesCompleted === 5) {
                $badgeKey = "explorer_5_{$childId}";
                if (!ProgressEvent::where('idempotency_key', $badgeKey)->exists()) {
                    ProgressEvent::create([
                        'child_id' => $child->id,
                        'event_type' => 'badge_earned',
                        'idempotency_key' => $badgeKey,
                        'metadata' => [
                            'badge_name' => 'Story Explorer',
                            'badge_icon' => '🗺️',
                            'description' => 'Completed 5 stories',
                            'reward_points' => 50,
                        ],
                        'recorded_at' => now(),
                        'synced_at' => now(),
                    ]);
                    $badgesAwarded[] = 'explorer_5';
                }
            }

            // 10 Stories: Super Reader Badge
            if ($storiesCompleted === 10) {
                $badgeKey = "super_reader_10_{$childId}";
                if (!ProgressEvent::where('idempotency_key', $badgeKey)->exists()) {
                    ProgressEvent::create([
                        'child_id' => $child->id,
                        'event_type' => 'badge_earned',
                        'idempotency_key' => $badgeKey,
                        'metadata' => [
                            'badge_name' => 'Super Reader',
                            'badge_icon' => '📚',
                            'description' => 'Completed 10 stories',
                            'reward_points' => 100,
                        ],
                        'recorded_at' => now(),
                        'synced_at' => now(),
                    ]);
                    $badgesAwarded[] = 'super_reader_10';
                }
            }

            // Vocab Mastery Milestone
            $vocabLearned = $child->progressEvents()
                ->where('event_type', 'vocab_learned')
                ->count();

            if ($vocabLearned === 20) {
                $badgeKey = "vocab_master_20_{$childId}";
                if (!ProgressEvent::where('idempotency_key', $badgeKey)->exists()) {
                    ProgressEvent::create([
                        'child_id' => $child->id,
                        'event_type' => 'badge_earned',
                        'idempotency_key' => $badgeKey,
                        'metadata' => [
                            'badge_name' => 'Word Master',
                            'badge_icon' => '🧠',
                            'description' => 'Learned 20 words',
                            'reward_points' => 75,
                        ],
                        'recorded_at' => now(),
                        'synced_at' => now(),
                    ]);
                    $badgesAwarded[] = 'vocab_master_20';
                }
            }

            // Consistency Streak (5 days in a row)
            $uniqueDays = $child->progressEvents()
                ->select(DB::raw('DATE(recorded_at) as day'))
                ->distinct()
                ->orderBy('day', 'desc')
                ->pluck('day');

            if ($this->isConsecutiveDays($uniqueDays, 5)) {
                $badgeKey = "streak_5_{$childId}";
                if (!ProgressEvent::where('idempotency_key', $badgeKey)->exists()) {
                    ProgressEvent::create([
                        'child_id' => $child->id,
                        'event_type' => 'badge_earned',
                        'idempotency_key' => $badgeKey,
                        'metadata' => [
                            'badge_name' => 'Consistency Champion',
                            'badge_icon' => '🔥',
                            'description' => '5 day learning streak',
                            'reward_points' => 60,
                        ],
                        'recorded_at' => now(),
                        'synced_at' => now(),
                    ]);
                    $badgesAwarded[] = 'streak_5';
                }
            }
        }

        return array_unique($badgesAwarded);
    }

    /**
     * Check if days form a consecutive sequence (most recent N days consecutive)
     */
    private function isConsecutiveDays(Collection $days, int $count = 5): bool
    {
        if ($days->count() < $count) {
            return false;
        }

        $dayArray = $days->slice(0, $count)->reverse()->all();
        $today = now()->startOfDay();

        for ($i = 0; $i < $count; $i++) {
            $expectedDate = $today->copy()->subDays($i)->toDateString();
            if (!in_array($expectedDate, $dayArray)) {
                return false;
            }
        }

        return true;
    }

    /**
     * GET /api/v1/sync/status
     * Get sync status for mobile app
     */
    public function status(): JsonResponse
    {
        $userId = auth()->id();
        $lastSync = SyncEvent::where('user_id', $userId)
            ->latest('created_at')
            ->first();

        return response()->json([
            'last_sync_at' => $lastSync?->created_at,
            'last_sync_events_processed' => $lastSync?->events_processed ?? 0,
            'is_syncing' => false, // For future use with job queues
        ]);
    }
}
