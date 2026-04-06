<?php

namespace App\Http\Controllers\Api;

use App\Models\ChildProfile;
use App\Models\ProgressEvent;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgressController extends Controller
{
    /**
     * POST /api/v1/progress/events
     * Record single progress event with idempotency
     */
    public function recordEvent(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'child_id' => 'required|integer|exists:child_profiles,id',
                'comic_id' => 'nullable|integer|exists:comics,id',
                'event_type' => 'required|in:story_started,story_completed,panel_viewed,vocab_learned,badge_earned,exercise_completed',
                'duration_seconds' => 'nullable|integer|min:0',
                'score' => 'nullable|integer|min:0|max:100',
                'idempotency_key' => 'nullable|string|unique:progress_events,idempotency_key',
                'payload' => 'nullable|array',
                'recorded_at' => 'nullable|date_format:Y-m-d H:i:s',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        }

        try {
            $child = ChildProfile::findOrFail($validated['child_id']);

            // Authorization: parent can record for own child, teacher/admin for org children
            if ($child->parent_user_id !== auth()->id() && 
                !auth()->user()->hasRole(['teacher', 'org_admin', 'super_admin']) &&
                auth()->user()->org_id !== $child->org_id) {
                return ApiResponse::forbidden('You cannot record progress for this child');
            }

            // Check for duplicate (idempotency)
            $idempotencyKey = $validated['idempotency_key'] ?? Str::uuid()->toString();
            if (ProgressEvent::where('idempotency_key', $idempotencyKey)->exists()) {
                return ApiResponse::success(
                    ProgressEvent::where('idempotency_key', $idempotencyKey)->first(),
                    'Event already recorded (idempotent)'
                );
            }

            $event = ProgressEvent::create([
                'child_id' => $validated['child_id'],
                'comic_id' => $validated['comic_id'],
                'tribe_id' => $validated['tribe_id'] ?? null,
                'panel_number' => $validated['panel_number'] ?? null,
                'event_type' => $validated['event_type'],
                'duration_seconds' => $validated['duration_seconds'] ?? null,
                'score' => $validated['score'] ?? null,
                'idempotency_key' => $idempotencyKey,
                'metadata' => $validated['payload'] ?? [],
                'recorded_at' => $validated['recorded_at'] ?? now(),
                'synced_at' => now(),
            ]);

            return ApiResponse::success($event, 'Progress recorded successfully', 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::notFound('Child profile not found');
        } catch (\Exception $e) {
            \Log::error('Progress record error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to record progress', $e);
        }
    }

    /**
     * GET /api/v1/progress/child/{id}
     * Child progress summary with stats and recent events
     */
    public function childProgress(ChildProfile $child): JsonResponse
    {
        try {
            // Authorization
            if ($child->parent_user_id !== auth()->id() && 
                !auth()->user()->hasRole(['teacher', 'org_admin', 'super_admin']) &&
                auth()->user()->org_id !== $child->org_id) {
                return ApiResponse::forbidden('You cannot view progress for this child');
            }

            $events = $child->progressEvents()->latest()->get();
            $completedComics = $child->progressEvents()
                ->where('event_type', 'story_completed')
                ->distinct('comic_id')
                ->count();
            $badgesEarned = $child->progressEvents()
                ->where('event_type', 'badge_earned')
                ->count();

            $totalTime = $child->progressEvents()
                ->whereNotNull('duration_seconds')
                ->sum('duration_seconds');

            return ApiResponse::success([
                'child' => $child,
                'stats' => [
                    'stories_completed' => $completedComics,
                    'badges_earned' => $badgesEarned,
                    'total_time_seconds' => $totalTime,
                ],
                'recent_events' => $events->take(10),
            ], 'Progress retrieved successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::notFound('Child profile not found');
        } catch (\Exception $e) {
            \Log::error('Progress show error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to retrieve progress', $e);
        }
    }

    /**
     * GET /api/v1/child-profiles
     * Parent's child profiles with progress stats
     */
    public function childProfiles(Request $request): JsonResponse
    {
        try {
            $profiles = auth()->user()->childProfiles()
                ->with('ageProfile', 'progressEvents')
                ->get()
                ->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'avatar' => $child->avatar,
                        'age_profile_id' => $child->age_profile_id,
                        'date_of_birth' => $child->date_of_birth->toDateString(),
                        'stats' => [
                            'stories_completed' => $child->progressEvents()
                                ->where('event_type', 'story_completed')
                                ->distinct('comic_id')
                                ->count(),
                            'badges_earned' => $child->progressEvents()
                                ->where('event_type', 'badge_earned')
                                ->count(),
                        ],
                    ];
                });

            return ApiResponse::success($profiles, 'Child profiles retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Child profiles error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to retrieve child profiles', $e);
        }
    }

    /**
     * POST /api/v1/child-profiles
     * Create child profile
     */
    public function createChildProfile(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'date_of_birth' => 'required|date|before:today',
                'avatar' => 'nullable|string|max:255',
                'preferred_tribe_ids' => 'nullable|array',
                'preferred_tribe_ids.*' => 'integer|exists:tribes,id',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        }

        try {
            // Calculate age profile based on date of birth
            $age = now()->diffInYears($validated['date_of_birth']);
            $ageProfile = \App\Models\AgeProfile::where('age_min', '<=', $age)
                ->where('age_max', '>=', $age)
                ->first() ?? \App\Models\AgeProfile::first();

            if (!$ageProfile) {
                return ApiResponse::error('No age profiles available', null, 500);
            }

            $profile = auth()->user()->childProfiles()->create([
                'org_id' => auth()->user()->org_id,
                'age_profile_id' => $ageProfile->id,
                'name' => $validated['name'],
                'date_of_birth' => $validated['date_of_birth'],
                'avatar' => $validated['avatar'],
                'preferred_tribe_ids' => $validated['preferred_tribe_ids'] ?? [],
            ]);

            return ApiResponse::success($profile, 'Child profile created successfully', 201);

        } catch (\Exception $e) {
            \Log::error('Child profile creation error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to create child profile', $e);
        }
    }

    /**
     * DELETE /api/v1/child-profiles/{id}
     * Delete child profile
     */
    public function deleteChildProfile(ChildProfile $child): JsonResponse
    {
        try {
            // Authorization: only parent can delete own children
            if ($child->parent_user_id !== auth()->id()) {
                return ApiResponse::forbidden('You cannot delete this child profile');
            }

            $child->delete();

            return ApiResponse::success(null, 'Child profile deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Child delete error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to delete child profile', $e);
        }
    }

    /**
     * PUT /api/v1/child-profiles/{id}
     * Update child profile
     */
    public function updateChildProfile(ChildProfile $child, Request $request): JsonResponse
    {
        try {
            // Authorization
            if ($child->parent_user_id !== auth()->id()) {
                return ApiResponse::forbidden('You cannot update this child profile');
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:100',
                'avatar' => 'sometimes|string|max:255',
                'preferred_tribe_ids' => 'sometimes|array',
                'preferred_tribe_ids.*' => 'integer|exists:tribes,id',
            ]);

            $child->update($validated);

            return ApiResponse::success($child, 'Child profile updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            \Log::error('Child update error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to update child profile', $e);
        }
    }
}

