<?php

namespace App\Http\Controllers\Api;

use App\Models\LessonPlan;
use App\Models\Comic;
use App\Models\ChildProfile;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    /**
     * GET /api/v1/lesson-plans
     * Get teacher's lesson plans
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Authorization: only teachers
            if (!auth()->user()->hasRole('teacher')) {
                return ApiResponse::forbidden('Only teachers can view lesson plans');
            }

            $status = $request->query('status');
            $perPage = $request->query('per_page', 20);

            $query = LessonPlan::where('teacher_id', auth()->id())
                ->with('childProfiles');

            if ($status) {
                $query->where('status', $status);
            }

            $plans = $query->paginate($perPage);

            return ApiResponse::paginated(
                $plans->items(),
                $plans->total(),
                $plans->perPage(),
                $plans->currentPage(),
                'Lesson plans retrieved successfully'
            );

        } catch (\Exception $e) {
            \Log::error('Lesson plan list error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to retrieve lesson plans', $e);
        }
    }

    /**
     * POST /api/v1/lesson-plans
     * Create lesson plan
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Authorization
            if (!auth()->user()->hasRole('teacher')) {
                return ApiResponse::forbidden('Only teachers can create lesson plans');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'classroom_id' => 'required|string|max:100',
                'assigned_comic_ids' => 'required|array|min:1',
                'assigned_comic_ids.*' => 'integer|exists:comics,id',
                'assigned_tribe_ids' => 'nullable|array',
                'assigned_tribe_ids.*' => 'integer|exists:tribes,id',
                'scheduled_at' => 'required|date|after:now',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        }

        try {
            $plan = LessonPlan::create([
                'org_id' => auth()->user()->org_id,
                'teacher_id' => auth()->id(),
                'title' => $validated['title'],
                'classroom_id' => $validated['classroom_id'],
                'assigned_comic_ids' => $validated['assigned_comic_ids'],
                'assigned_tribe_ids' => $validated['assigned_tribe_ids'] ?? [],
                'scheduled_at' => $validated['scheduled_at'],
                'status' => 'scheduled',
            ]);

            return ApiResponse::success($plan, 'Lesson plan created successfully', 201);

        } catch (\Exception $e) {
            \Log::error('Lesson plan creation error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to create lesson plan', $e);
        }
    }

    /**
     * PUT /api/v1/lesson-plans/{id}
     * Update lesson plan
     */
    public function update(LessonPlan $lessonPlan, Request $request): JsonResponse
    {
        try {
            // Authorization: teacher owns this lesson plan
            if ($lessonPlan->teacher_id !== auth()->id() && !auth()->user()->hasRole('super_admin')) {
                return ApiResponse::forbidden('You cannot update this lesson plan');
            }

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'assigned_comic_ids' => 'sometimes|array|min:1',
                'assigned_comic_ids.*' => 'integer|exists:comics,id',
                'assigned_tribe_ids' => 'sometimes|array',
                'assigned_tribe_ids.*' => 'integer|exists:tribes,id',
                'status' => 'sometimes|in:draft,scheduled,completed,cancelled',
            ]);

            $lessonPlan->update($validated);

            return ApiResponse::success($lessonPlan, 'Lesson plan updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            \Log::error('Lesson plan update error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to update lesson plan', $e);
        }
    }

    /**
     * DELETE /api/v1/lesson-plans/{id}
     * Delete lesson plan
     */
    public function destroy(LessonPlan $lessonPlan): JsonResponse
    {
        try {
            // Authorization
            if ($lessonPlan->teacher_id !== auth()->id() && !auth()->user()->hasRole('super_admin')) {
                return ApiResponse::forbidden('You cannot delete this lesson plan');
            }

            $lessonPlan->delete();

            return ApiResponse::success(null, 'Lesson plan deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Lesson plan delete error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to delete lesson plan', $e);
        }
    }

    /**
     * POST /api/v1/lesson-plans/{id}/complete
     * Mark lesson plan as completed
     */
    public function complete(LessonPlan $lessonPlan): JsonResponse
    {
        try {
            // Authorization
            if ($lessonPlan->teacher_id !== auth()->id()) {
                return ApiResponse::forbidden('You cannot update this lesson plan');
            }

            $lessonPlan->update([
                'status' => 'completed',
            ]);

            return ApiResponse::success($lessonPlan, 'Lesson plan marked as completed');

        } catch (\Exception $e) {
            \Log::error('Lesson complete error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to complete lesson plan', $e);
        }
    }
}
