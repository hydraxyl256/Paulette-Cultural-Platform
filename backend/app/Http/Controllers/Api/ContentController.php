<?php

namespace App\Http\Controllers\Api;

use App\Models\AgeProfile;
use App\Models\Comic;
use App\Models\ChildProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController
{
    /**
     * GET /api/v1/age-profiles
     * Get all age profile configs
     */
    public function ageProfiles(): JsonResponse
    {
        $profiles = AgeProfile::all();

        return response()->json($profiles);
    }

    /**
     * GET /api/v1/content/manifest
     * Offline content manifest (org-scoped)
     */
    public function manifest(Request $request): JsonResponse
    {
        $orgId = auth()->user()->org_id;

        $comics = Comic::where('org_id', $orgId)
            ->where('status', 'published')
            ->with('tribe', 'panels')
            ->get();

        return response()->json([
            'org_id' => $orgId,
            'comics' => $comics,
            'version' => now()->timestamp,
        ]);
    }

    /**
     * GET /api/v1/comics/{id}/panels
     * Get panels for offline bundle
     */
    public function panels(Comic $comic): JsonResponse
    {
        $this->authorize('view', $comic);

        $panels = $comic->panels()->get();

        return response()->json([
            'comic_id' => $comic->id,
            'panels' => $panels,
        ]);
    }

    /**
     * GET /api/v1/bundles/{tribe_id}
     * Download .ckb bundle (signed URL)
     */
    public function bundle(Request $request, int $tribeId): JsonResponse
    {
        $orgId = auth()->user()->org_id;

        $comics = Comic::where('tribe_id', $tribeId)
            ->where('org_id', $orgId)
            ->where('status', 'published')
            ->get();

        // Generate bundle hash and signed URL
        $bundleHash = md5(json_encode($comics->pluck('id')));

        return response()->json([
            'tribe_id' => $tribeId,
            'bundle_hash' => $bundleHash,
            'comics' => $comics,
            'download_url' => route('api.bundle.download', ['hash' => $bundleHash]),
        ]);
    }
}
