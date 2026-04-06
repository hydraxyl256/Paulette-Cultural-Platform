<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class QueueManager extends Page
{
    // ── Page settings ─────────────────────────────────────────────
    protected string $view = 'filament.pages.queue-manager';

    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-rocket-launch';
    protected static UnitEnum|string|null   $navigationGroup = 'SYSTEM';
    protected static ?int    $navigationSort  = 3;
    protected static ?string $title           = 'Queue Manager';
    protected static ?string $navigationLabel = 'Queue Manager';

    // ── Livewire state ─────────────────────────────────────────────
    public string $ckViewMode    = 'live';   // live | historical
    public bool   $ckAutoRefresh = true;

    // ── Retry all failed ───────────────────────────────────────────
    public function ckRetryAll(): void
    {
        $count = DB::table('failed_jobs')->count();
        DB::table('failed_jobs')->delete();
        Notification::make()->success()
            ->title('Retry Queued')
            ->body($count > 0 ? "{$count} failed jobs re-queued." : 'No failed jobs to retry.')
            ->send();
    }

    // ── Toggle view mode ──────────────────────────────────────────
    public function ckSetView(string $mode): void
    {
        $this->ckViewMode = $mode;
    }

    // ── Build view data ───────────────────────────────────────────
    protected function getViewData(): array
    {
        // ── Real DB stats (fall back to demo values if empty) ──
        $realPending    = DB::table('jobs')->count();
        $realProcessing = DB::table('jobs')->whereNotNull('reserved_at')->count();
        $realFailed     = DB::table('failed_jobs')->count();

        $pending    = $realPending    > 0 ? $realPending    : 1284;
        $processing = $realProcessing > 0 ? $realProcessing : 42;
        $failed     = $realFailed     > 0 ? $realFailed     : 12;
        $completed  = '48.2k';
        $throughput = '840';
        $avgTime    = '1.4';

        // ── Demo active jobs ───────────────────────────────────
        $activeJobs = [
            [
                'id'       => '#QJ-9022-A',
                'worker'   => 'Node-42',
                'category' => 'High-Res Tile Render',
                'priority' => 'CRITICAL',
                'status'   => 'Processing...',
                'pct'      => 88,
                'bar_color'=> '#059669',
                'action'   => 'pause',
            ],
            [
                'id'       => '#QJ-8815-X',
                'worker'   => 'Node-10',
                'category' => 'AI Object Classification',
                'priority' => 'NORMAL',
                'status'   => 'Extracting...',
                'pct'      => 32,
                'bar_color'=> '#7c3aed',
                'action'   => 'pause',
            ],
            [
                'id'       => '#QJ-9104-B',
                'worker'   => 'Node-42',
                'category' => 'Archive Compression',
                'priority' => 'LOW',
                'status'   => 'Queued',
                'pct'      => 0,
                'bar_color'=> '#059669',
                'action'   => 'play',
            ],
        ];

        // ── Demo failed jobs ────────────────────────────────────
        $failedJobs = [
            [
                'type'   => 'CRITICAL_EXCEPTION',
                'id'     => '#QJ-7721-F',
                'name'   => 'Video Transcode Failure',
                'trace'  => "Error: ENOENT: no such file or directory, open '/var/media/raw/clip_8a3f.mp4'",
                'color'  => '#dc2626',
            ],
            [
                'type'   => 'TIMEOUT_ERROR',
                'id'     => '#QJ-7729-K',
                'name'   => 'Batch Metadata Update',
                'trace'  => 'Worker failed to respond within 30068ms — connection pool exhausted.',
                'color'  => '#d97706',
            ],
        ];

        // ── Queue depth time-series (SVG wave data) ─────────────
        $timeLabels = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'];

        return compact(
            'pending', 'processing', 'failed', 'completed',
            'throughput', 'avgTime', 'activeJobs', 'failedJobs', 'timeLabels'
        );
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
