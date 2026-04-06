<?php

namespace App\Http\Controllers\Api;

use App\Models\Tribe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TribeController
{
    /**
     * GET /api/v1/tribes
     * List tribes (org-scoped)
     */
    public function index(Request $request): JsonResponse
    {
        $tribes = Tribe::where('is_active', true)->get();

        return response()->json([
            'data' => $tribes,
            'count' => $tribes->count(),
        ]);
    }

    /**
     * GET /api/v1/tribes/{id}
     * Get tribe details with comics
     */
    public function show(Tribe $tribe): JsonResponse
    {
        $tribe->load(['comics' => function ($query) {
            $query->where('status', 'published');
        }]);

        return response()->json($tribe);
    }

    /**
     * GET /api/v1/tribes/{id}/comics
     * Comics for tribe (age-filtered)
     */
    public function comics(Request $request, Tribe $tribe): JsonResponse
    {
        $ageProfile = $request->query('age_profile_id');

        $comics = $tribe->comics()
            ->where('status', 'published')
            ->when($ageProfile, fn($q) => $q->where('age_profile_id', $ageProfile))
            ->with('panels')
            ->get();

        return response()->json([
            'tribe' => $tribe,
            'comics' => $comics,
        ]);
    }
}
