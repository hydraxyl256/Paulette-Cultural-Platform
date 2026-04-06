<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Support\Str;

/**
 * Offline Sync Service
 * 
 * Handles syncing of offline-recorded progress events to the server
 * when connection is restored. Designed for mobile/Expo app usage.
 * 
 * SQLite Schema (client side):
 * - offline_events table: Stores events recorded while offline
 * - sync_status table: Tracks sync status and metadata
 */
class OfflineSyncService
{
    /**
     * Get sync queue status from server
     */
    public function checkSyncStatus(string $token): array
    {
        try {
            $response = HTTP::withToken($token)
                ->get('/api/v1/sync/status');

            if ($response->successful()) {
                return [
                    'status' => 'ok',
                    'pending_events' => $response->json('pending_events'),
                    'last_sync' => $response->json('last_sync'),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Server sync check failed',
            ];

        } catch (\Exception $e) {
            Log::error('Sync status check failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Network error',
                'offline' => true,
            ];
        }
    }

    /**
     * Sync offline events to server
     * 
     * Returns: ['status' => 'success|partial|failed', 'processed' => n, 'skipped' => n]
     */
    public function syncEvents(array $events, string $token): array
    {
        try {
            if (empty($events)) {
                return [
                    'status' => 'success',
                    'processed' => 0,
                    'skipped' => 0,
                    'message' => 'No events to sync',
                ];
            }

            // Batch events (max 100 per request)
            $batches = array_chunk($events, 100);
            $totalProcessed = 0;
            $totalSkipped = 0;
            $allSuccessful = true;

            foreach ($batches as $batch) {
                try {
                    $response = HTTP::withToken($token)
                        ->timeout(30)
                        ->post('/api/v1/sync', ['events' => $batch]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $totalProcessed += $data['events_processed'];
                        $totalSkipped += $data['events_skipped'];
                    } else {
                        $allSuccessful = false;
                        Log::warning('Sync batch failed', [
                            'status' => $response->status(),
                            'response' => $response->body(),
                        ]);
                    }

                } catch (\Exception $e) {
                    $allSuccessful = false;
                    Log::error('Sync batch error', ['error' => $e->getMessage()]);
                }
            }

            return [
                'status' => $allSuccessful ? 'success' : 'partial',
                'processed' => $totalProcessed,
                'skipped' => $totalSkipped,
                'message' => $allSuccessful ? 'All events synced' : 'Some events failed to sync',
            ];

        } catch (\Exception $e) {
            Log::error('Sync error', ['error' => $e->getMessage()]);
            return [
                'status' => 'failed',
                'processed' => 0,
                'skipped' => count($events),
                'message' => 'Sync failed: ' . $e->getMessage(),
                'offline' => true,
            ];
        }
    }

    /**
     * Generate idempotency key for offline events
     */
    public function generateIdempotencyKey(string $deviceId, string $eventType, int $timestamp): string
    {
        return sprintf(
            'mobile-%s-%s-%d-%s',
            substr($deviceId, 0, 12),
            $eventType,
            $timestamp,
            Str::random(8)
        );
    }

    /**
     * Verify downloaded bundle integrity
     */
    public function verifyBundleIntegrity(string $filePath, string $expectedHash): bool
    {
        try {
            if (!file_exists($filePath)) {
                return false;
            }

            $actualHash = hash_file('sha256', $filePath);
            return strtolower($actualHash) === strtolower($expectedHash);

        } catch (\Exception $e) {
            Log::error('Bundle verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Calculate time since last successful sync
     */
    public function getTimeSinceLastSync(?string $lastSyncTime): string
    {
        if (!$lastSyncTime) {
            return 'Never synced';
        }

        $last = \Carbon\Carbon::parse($lastSyncTime);
        $diff = now()->diffForHumans($last, ['parts' => 1]);

        return "Synced {$diff}";
    }

    /**
     * Get estimated data usage for sync
     */
    public function estimateDataUsage(array $events): array
    {
        // Rough estimate: ~200 bytes per event
        $estimatedBytes = count($events) * 200;

        return [
            'events_count' => count($events),
            'estimated_bytes' => $estimatedBytes,
            'estimated_kb' => round($estimatedBytes / 1024, 2),
            'estimated_mb' => round($estimatedBytes / (1024 * 1024), 2),
        ];
    }
}
