<?php

namespace App\Http\Controllers\Api;

use App\Models\ProgressEvent;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncStatusController extends Controller
{
    /**
     * GET /api/v1/sync/status
     * Get current sync status for mobile app
     */
    public function status(): JsonResponse
    {
        try {
            $userId = auth()->id();
            $lastSync = ProgressEvent::where('synced_at', '!=', null)
                ->latest('synced_at')
                ->value('synced_at');

            $pendingLocal = request()->query('pending_count', 0); // From mobile app

            return ApiResponse::success([
                'user_id' => $userId,
                'last_sync' => $lastSync?->toIso8601String(),
                'pending_events' => $pendingLocal,
                'is_connected' => true,
                'server_time' => now()->toIso8601String(),
            ], 'Sync status retrieved');

        } catch (\Exception $e) {
            \Log::error('Sync status error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to get sync status', $e);
        }
    }

    /**
     * GET /api/v1/sync/history
     * Get sync history for debugging
     */
    public function history(Request $request): JsonResponse
    {
        try {
            $limit = $request->query('limit', 50);

            $history = ProgressEvent::where('user_id', auth()->id())
                ->whereNotNull('synced_at')
                ->latest('synced_at')
                ->limit($limit)
                ->get()
                ->map(function ($event) {
                    return [
                        'event_type' => $event->event_type,
                        'child_id' => $event->child_id,
                        'synced_at' => $event->synced_at->toIso8601String(),
                        'recorded_at' => $event->recorded_at?->toIso8601String(),
                        'delay_seconds' => $event->synced_at?->diffInSeconds($event->recorded_at),
                    ];
                });

            return ApiResponse::success([
                'history' => $history,
                'total_synced_events' => ProgressEvent::where('user_id', auth()->id())->whereNotNull('synced_at')->count(),
            ], 'Sync history retrieved');

        } catch (\Exception $e) {
            \Log::error('Sync history error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to get sync history', $e);
        }
    }
}
