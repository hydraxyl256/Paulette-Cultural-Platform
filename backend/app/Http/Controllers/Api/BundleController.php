<?php

namespace App\Http\Controllers\Api;

use App\Models\Comic;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BundleController extends Controller
{
    /**
     * GET /api/v1/bundles/{tribe_id}
     * Get bundles for a tribe (list available downloads)
     */
    public function index(int $tribeId): JsonResponse
    {
        try {
            $orgId = auth()->user()->org_id;

            $comics = Comic::where('tribe_id', $tribeId)
                ->where('org_id', $orgId)
                ->where('status', 'published')
                ->whereNotNull('bundle_path')
                ->get()
                ->map(function ($comic) {
                    return [
                        'id' => $comic->id,
                        'title' => $comic->title,
                        'bundle_hash' => $comic->bundle_hash,
                        'age_min' => $comic->age_min,
                        'age_max' => $comic->age_max,
                        'panels_count' => $comic->panels()->count(),
                        'bundle_ready' => true,
                    ];
                });

            return ApiResponse::success([
                'tribe_id' => $tribeId,
                'comics' => $comics,
            ], 'Bundles available for tribe');

        } catch (\Exception $e) {
            \Log::error('Bundle list error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to list bundles', $e);
        }
    }

    /**
     * GET /api/v1/bundles/{comic_id}/download
     * Generate download URL for a bundle
     */
    public function download(Comic $comic): JsonResponse
    {
        try {
            // Authorization
            if (auth()->user()->org_id !== $comic->org_id && auth()->user()->role !== 'super_admin') {
                return ApiResponse::forbidden('You do not have access to this bundle');
            }

            // Check if bundle exists
            if (!$comic->bundle_path) {
                return ApiResponse::error(
                    'Bundle not available',
                    ['message' => 'Please publish the comic first to generate a bundle'],
                    404
                );
            }

            // Verify file exists in S3
            $disk = Storage::disk('s3');
            if (!$disk->exists($comic->bundle_path)) {
                return ApiResponse::error(
                    'Bundle file missing',
                    ['message' => 'Bundle file not found in storage. Please regenerate.'],
                    404
                );
            }

            // Generate signed download URL (valid for 1 hour)
            $signedUrl = $disk->temporaryUrl(
                $comic->bundle_path,
                now()->addMinutes(60),
                [
                    'ResponseContentDisposition' => "attachment; filename=\"{$comic->id}_{$comic->title}.ckb\"",
                ]
            );

            // Get file size
            $fileSize = $disk->size($comic->bundle_path);

            return ApiResponse::success([
                'comic_id' => $comic->id,
                'comic_title' => $comic->title,
                'bundle_hash' => $comic->bundle_hash,
                'download_url' => $signedUrl,
                'file_size_bytes' => $fileSize,
                'file_size_mb' => round($fileSize / (1024 * 1024), 2),
                'expires_in_minutes' => 60,
                'expires_at' => now()->addMinutes(60)->toIso8601String(),
            ], 'Download URL generated successfully');

        } catch (\Exception $e) {
            \Log::error('Bundle download error', ['comic_id' => $comic->id, 'error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to generate download URL', $e);
        }
    }

    /**
     * POST /api/v1/bundles/{comic_id}/verify
     * Verify bundle integrity using hash
     */
    public function verify(Comic $comic, Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'hash' => 'required|string|size:64', // SHA256
            ]);

            if ($validated['hash'] !== $comic->bundle_hash) {
                return ApiResponse::error(
                    'Bundle verification failed',
                    ['message' => 'Bundle hash mismatch. Bundle may be corrupted.'],
                    422
                );
            }

            return ApiResponse::success([
                'verified' => true,
                'bundle_hash' => $comic->bundle_hash,
                'message' => 'Bundle integrity verified',
            ], 'Bundle verified successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            \Log::error('Bundle verify error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to verify bundle', $e);
        }
    }
}
