<?php

namespace App\Http\Controllers\Api;

use App\Models\Comic;
use App\Models\Tribe;
use App\Models\ChildProfile;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    /**
     * GET /api/v1/comics
     * List all published comics (org-scoped, paginated)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 20);
            $page = $request->query('page', 1);
            $tribeId = $request->query('tribe_id');
            $ageProfile = $request->query('age_profile_id');

            $orgId = auth()->user()->org_id;

            $query = Comic::where('org_id', $orgId)
                ->where('status', 'published')
                ->with('tribe', 'panels');

            if ($tribeId) {
                $query->where('tribe_id', $tribeId);
            }

            if ($ageProfile) {
                $query->whereBetween('age_min', [0, $ageProfile])
                      ->whereBetween('age_max', [$ageProfile, 12]);
            }

            $comics = $query->paginate($perPage, ['*'], 'page', $page);

            return ApiResponse::paginated(
                $comics->items(),
                $comics->total(),
                $comics->perPage(),
                $comics->currentPage(),
                'Comics retrieved successfully'
            );

        } catch (\Exception $e) {
            \Log::error('Comic list error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to retrieve comics', $e);
        }
    }

    /**
     * GET /api/v1/comics/{id}
     * Get single comic with full details
     */
    public function show(Comic $comic): JsonResponse
    {
        try {
            // Authorization: user must be in same org or be super admin
            if (auth()->user()->org_id !== $comic->org_id && auth()->user()->role !== 'super_admin') {
                return ApiResponse::forbidden('You do not have access to this comic');
            }

            $comic->load('tribe', 'panels', 'progressEvents');

            return ApiResponse::success($comic, 'Comic retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Comic show error', ['error' => $e->getMessage(), 'comic_id' => $comic->id]);
            return ApiResponse::serverError('Failed to retrieve comic', $e);
        }
    }

    /**
     * GET /api/v1/comics/{id}/download
     * Download .ckb bundle (with security check)
     */
    public function download(Comic $comic): JsonResponse
    {
        try {
            // Authorization check
            if (auth()->user()->org_id !== $comic->org_id && auth()->user()->role !== 'super_admin') {
                return ApiResponse::forbidden('You do not have access to download this comic');
            }

            // Check if bundle exists
            if (!$comic->bundle_path) {
                return ApiResponse::notFound('Bundle not available for this comic. Please publish the comic first.');
            }

            // Generate signed S3 URL
            $disk = \Illuminate\Support\Facades\Storage::disk('s3');
            $url = $disk->temporaryUrl(
                $comic->bundle_path,
                now()->addMinutes(30),
                ['ResponseContentDisposition' => "attachment; filename={$comic->id}.ckb"]
            );

            return ApiResponse::success([
                'comic_id' => $comic->id,
                'comic_title' => $comic->title,
                'bundle_hash' => $comic->bundle_hash,
                'download_url' => $url,
                'expires_at' => now()->addMinutes(30)->toIso8601String(),
                'file_size' => $disk->size($comic->bundle_path),
            ], 'Download URL generated');

        } catch (\Exception $e) {
            \Log::error('Comic download error', ['error' => $e->getMessage(), 'comic_id' => $comic->id]);
            return ApiResponse::serverError('Failed to generate download URL', $e);
        }
    }
}
