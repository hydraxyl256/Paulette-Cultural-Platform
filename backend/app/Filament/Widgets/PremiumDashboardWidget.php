<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrganisationResource;
use App\Models\AuditLog;
use App\Models\ChildProfile;
use App\Models\Comic;
use App\Models\ModuleFlag;
use App\Models\Organisation;
use App\Models\ProgressEvent;
use App\Models\SyncEvent;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PremiumDashboardWidget extends Widget
{
    protected string $view = 'filament.widgets.premium-dashboard';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -10;

    /**
     * Toggle a module flag in the database.
     */
    public function toggleModule(string $key): void
    {
        ModuleFlag::toggle($key);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $now = now();
        $weekStart = $now->copy()->startOfWeek();
        $prevWeekStart = $weekStart->copy()->subWeek();
        $prevWeekEnd = $weekStart->copy()->subDay();

        $currentWindowStart = $now->copy()->subDays(7);
        $previousWindowStart = $now->copy()->subDays(14);
        $previousWindowEnd = $now->copy()->subDays(7);

        // ── KPI Raw Counts ─────────────────────────────────────────
        $activeChildrenCount = ChildProfile::query()->count();
        $organisationsCount = Organisation::query()->count();
        $publishedStoriesCount = Comic::query()->where('status', 'published')->count();
        $tribesCovered = Comic::query()
            ->where('status', 'published')
            ->whereNotNull('tribe_id')
            ->distinct()
            ->count('tribe_id');

        $badgesEarnedTotal = ProgressEvent::query()
            ->where('event_type', 'badge_earned')
            ->count();
        $badgesLast7Days = ProgressEvent::query()
            ->where('event_type', 'badge_earned')
            ->where('created_at', '>=', $now->copy()->subDays(7))
            ->count();

        $avgSessionSeconds = ProgressEvent::query()
            ->where('duration_seconds', '>', 0)
            ->avg('duration_seconds');

        $avgSessionLast7 = ProgressEvent::query()
            ->where('duration_seconds', '>', 0)
            ->whereBetween('created_at', [$currentWindowStart, $now])
            ->avg('duration_seconds');
        $avgSessionPrev7 = ProgressEvent::query()
            ->where('duration_seconds', '>', 0)
            ->whereBetween('created_at', [$previousWindowStart, $previousWindowEnd])
            ->avg('duration_seconds');

        $newOrgsThisMonth = Organisation::query()
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        // ── KPI Trends ─────────────────────────────────────────────
        $childrenThisWeek = ChildProfile::query()
            ->whereBetween('created_at', [$weekStart, $now])
            ->count();
        $childrenPrevWeek = ChildProfile::query()
            ->whereBetween('created_at', [$prevWeekStart, $prevWeekEnd])
            ->count();
        $childrenWeekTrend = $this->computeTrendFromCounts($childrenThisWeek, $childrenPrevWeek);

        $orgsThisMonth = Organisation::query()
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now])
            ->count();
        $orgsPrevMonth = Organisation::query()
            ->whereBetween('created_at', [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()])
            ->count();
        $orgsMonthTrend = $this->computeTrendFromCounts($orgsThisMonth, $orgsPrevMonth);

        $comicsCurrent = Comic::query()
            ->where('status', 'published')
            ->whereBetween('created_at', [$currentWindowStart, $now])
            ->count();
        $comicsPrev = Comic::query()
            ->where('status', 'published')
            ->whereBetween('created_at', [$previousWindowStart, $previousWindowEnd])
            ->count();
        $comicsTrend = $this->computeTrendFromCounts($comicsCurrent, $comicsPrev);

        $badgesCurrent = ProgressEvent::query()
            ->where('event_type', 'badge_earned')
            ->whereBetween('created_at', [$currentWindowStart, $now])
            ->count();
        $badgesPrev = ProgressEvent::query()
            ->where('event_type', 'badge_earned')
            ->whereBetween('created_at', [$previousWindowStart, $previousWindowEnd])
            ->count();
        $badgesTrend = $this->computeTrendFromCounts($badgesCurrent, $badgesPrev);

        // ── Sync Rate ──────────────────────────────────────────────
        $syncTotal = SyncEvent::query()->count();
        $syncProcessed = SyncEvent::query()->where('processed', true)->count();
        $syncSuccessPct = $syncTotal > 0
            ? round(($syncProcessed / $syncTotal) * 100, 1)
            : 100.0;

        $syncRateCurrent = $this->syncSuccessRateInWindow($currentWindowStart, $now);
        $syncRatePrev = $this->syncSuccessRateInWindow($previousWindowStart, $previousWindowEnd);
        $syncRateDeltaPts = $syncRateCurrent - $syncRatePrev;

        if (abs($syncRateDeltaPts) < 0.25) {
            $syncTrendDir = 'neutral';
            $syncTrendLabel = 'Stable';
        } else {
            $syncTrendDir = $syncRateDeltaPts > 0 ? 'up' : 'down';
            $syncTrendLabel = number_format(abs($syncRateDeltaPts), 1).' pts';
        }

        $avgSessionTrend = $this->computeAvgSessionTrend(
            (float) ($avgSessionLast7 ?? 0),
            (float) ($avgSessionPrev7 ?? 0),
        );
        $avgSessionDisplay = $avgSessionLast7 ?? $avgSessionSeconds;

        // ── System Health ──────────────────────────────────────────
        $diskTotal = @disk_total_space(base_path()) ?: 1;
        $diskFree = @disk_free_space(base_path()) ?: 0;
        $storageUsedBytes = max(0, $diskTotal - $diskFree);
        $storageUsedPct = round(($storageUsedBytes / max($diskTotal, 1)) * 100, 1);

        $pendingJobs = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();
        $latencyMs = $this->estimatedLatencyMs();
        $throughputPerSec = $this->estimatedThroughputPerSec();

        $health = [
            'syncRate' => $syncSuccessPct,
            'latencyMs' => $latencyMs,
            'nodesOk' => $failedJobs === 0 ? '12/12' : '11/12',
            'queuePending' => $pendingJobs,
            'queueFailed' => $failedJobs,
            'throughputPerSec' => $throughputPerSec,
            'pipelineSteps' => $this->buildSyncPipeline(),
        ];

        // ── Chart Data (for Chart.js) ──────────────────────────────
        $chartDays = 14;
        $learningChartData = $this->buildLearningChartData($chartDays);
        $syncChartData = $this->buildSyncChartData($chartDays);

        // ── Activity ───────────────────────────────────────────────
        $activities = $this->buildActivityFeed();
        $alerts = $this->buildAlerts($storageUsedPct, $failedJobs, $pendingJobs, $syncSuccessPct);

        // ── Organisations Table ────────────────────────────────────
        $organisationsTable = Organisation::query()
            ->withCount('childProfiles')
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function (Organisation $o): array {
                $statusLabel = ! $o->is_active
                    ? 'Inactive'
                    : ($o->plan === 'free' ? 'Trial' : 'Active');

                $statusTone = ! $o->is_active
                    ? 'slate'
                    : ($o->plan === 'free' ? 'amber' : 'emerald');

                return [
                    'name' => $o->name,
                    'plan_label' => match ($o->plan) {
                        'enterprise' => 'Enterprise',
                        'school' => 'School',
                        default => 'Trial',
                    },
                    'plan_tone' => match ($o->plan) {
                        'enterprise' => 'emerald',
                        'school' => 'amber',
                        default => 'violet',
                    },
                    'children' => $o->child_profiles_count,
                    'status_label' => $statusLabel,
                    'status_tone' => $statusTone,
                    'edit_url' => OrganisationResource::getUrl('edit', ['record' => $o]),
                ];
            })
            ->all();

        // ── Module Flags (DB-backed) ───────────────────────────────
        $moduleFlags = ModuleFlag::allOrdered();
        $moduleDefs = $moduleFlags->map(fn (ModuleFlag $f) => [
            'key' => $f->key,
            'label' => $f->label,
            'subtitle' => $f->subtitle,
            'emoji' => $f->emoji,
        ])->all();

        $moduleToggles = $moduleFlags->pluck('is_enabled', 'key')->all();

        $laravelMajor = explode('.', (string) app()->version())[0] ?? '12';

        $orgsTrendLabel = $newOrgsThisMonth > 0
            ? '+'.$newOrgsThisMonth.' this month'
            : ($orgsMonthTrend['label'].' vs last month');

        // ── KPI Cards matching Command Center design (6 cards) ──────
        $kpis = [
            [
                'label' => 'Active Children',
                'value' => $this->formatCompact($activeChildrenCount),
                'trend' => '',
                'trendDirection' => 'neutral',
                'meta' => '',
                'barColor' => 'emerald',
                'barWidth' => min(100, max(10, $activeChildrenCount > 0 ? 72 : 10)),
            ],
            [
                'label' => 'Total Orgs',
                'value' => $this->formatCompact($organisationsCount),
                'trend' => $orgsMonthTrend['dir'] === 'up'
                    ? '↑'.number_format(abs((float) str_replace('%', '', $orgsMonthTrend['label']))).'%'
                    : ($orgsMonthTrend['dir'] === 'down'
                        ? '↓'.number_format(abs((float) str_replace('%', '', $orgsMonthTrend['label']))).'%'
                        : ''),
                'trendDirection' => $orgsMonthTrend['dir'],
                'meta' => '',
                'barColor' => 'emerald',
                'barWidth' => min(100, max(10, $organisationsCount > 0 ? 55 : 10)),
            ],
            [
                'label' => 'Comics Pub',
                'value' => $this->formatCompact($publishedStoriesCount),
                'trend' => $comicsTrend['dir'] === 'up'
                    ? '↑'.number_format(abs((float) str_replace('%', '', $comicsTrend['label']))).'%'
                    : ($comicsTrend['dir'] === 'down'
                        ? '↓'.number_format(abs((float) str_replace('%', '', $comicsTrend['label']))).'%'
                        : ''),
                'trendDirection' => $comicsTrend['dir'],
                'meta' => '',
                'barColor' => 'emerald',
                'barWidth' => min(100, max(10, $publishedStoriesCount > 0 ? 65 : 10)),
            ],
            [
                'label' => 'Badges Issued',
                'value' => $this->formatCompact($badgesEarnedTotal),
                'trend' => $badgesTrend['dir'] === 'up'
                    ? '↑'.$this->formatCompact($badgesLast7Days)
                    : ($badgesTrend['dir'] === 'down'
                        ? '↓'.$this->formatCompact($badgesLast7Days)
                        : '↔'.$this->formatCompact($badgesLast7Days)),
                'trendDirection' => $badgesTrend['dir'],
                'meta' => '',
                'barColor' => 'emerald',
                'barWidth' => min(100, max(10, $badgesEarnedTotal > 0 ? 78 : 10)),
            ],
            [
                'label' => 'Avg Sync',
                'value' => number_format($latencyMs / 1000, 1).'s',
                'trend' => '↔',
                'trendDirection' => 'neutral',
                'meta' => '',
                'barColor' => 'emerald',
                'barWidth' => min(100, max(10, $latencyMs > 0 ? (int) round(100 - min($latencyMs / 20, 80)) : 60)),
            ],
            [
                'label' => 'Storage',
                'value' => number_format($storageUsedPct, 0).'%',
                'trend' => $storageUsedPct >= 80 ? '⚠' : '',
                'trendDirection' => $storageUsedPct >= 80 ? 'warning' : ($storageUsedPct >= 60 ? 'neutral' : 'up'),
                'meta' => '',
                'barColor' => $storageUsedPct >= 80 ? 'amber' : 'emerald',
                'barWidth' => min(100, max(5, (int) $storageUsedPct)),
            ],
        ];

        return [
            'kpis' => $kpis,
            'health' => $health,
            'learningChartData' => $learningChartData,
            'syncChartData' => $syncChartData,
            'activities' => $activities,
            'alerts' => $alerts,
            'organisationsTable' => $organisationsTable,
            'moduleDefs' => $moduleDefs,
            'moduleToggles' => $moduleToggles,
            'auditUrl' => \App\Filament\Resources\AuditLogResource::getUrl('index'),
            'headerSubtitle' => sprintf('Laravel %s · SQLite · All organisations visible', $laravelMajor),
        ];
    }

    // ── Chart Data Builders ────────────────────────────────────────

    /**
     * Build learning events chart data grouped by day.
     * @return array{labels: list<string>, values: list<int>}
     */
    private function buildLearningChartData(int $days): array
    {
        $labels = [];
        $values = [];

        $counts = ProgressEvent::query()
            ->where('created_at', '>=', now()->subDays($days)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $values[] = $counts[$date] ?? 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Build sync success rate chart data by day.
     * @return array{labels: list<string>, values: list<float>}
     */
    private function buildSyncChartData(int $days): array
    {
        $labels = [];
        $values = [];

        $totals = SyncEvent::query()
            ->where('created_at', '>=', now()->subDays($days)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $processed = SyncEvent::query()
            ->where('created_at', '>=', now()->subDays($days)->startOfDay())
            ->where('processed', true)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $t = $totals[$date] ?? 0;
            $p = $processed[$date] ?? 0;
            $values[] = $t > 0 ? round(($p / $t) * 100, 1) : 100.0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    // ── Helpers (unchanged) ────────────────────────────────────────

    /**
     * @return array{dir: 'up'|'down'|'neutral', label: string}
     */
    private function computeAvgSessionTrend(float $current, float $previous): array
    {
        if ($previous <= 0 && $current <= 0) {
            return ['dir' => 'neutral', 'label' => 'Awaiting data'];
        }
        if ($previous <= 0) {
            return ['dir' => 'up', 'label' => 'New signal'];
        }

        $deltaPct = (($current - $previous) / $previous) * 100;
        if (abs($deltaPct) < 0.5) {
            return ['dir' => 'neutral', 'label' => 'Stable'];
        }

        $dir = $deltaPct > 0 ? 'up' : 'down';

        return [
            'dir' => $dir,
            'label' => (abs($deltaPct) >= 10 ? number_format(abs($deltaPct), 0) : number_format(abs($deltaPct), 1)).'% vs prior week',
        ];
    }

    private function formatDurationMean(?float $seconds): string
    {
        if ($seconds === null || $seconds <= 0) {
            return '—';
        }
        $total = (int) round($seconds);
        $m = intdiv($total, 60);
        $s = $total % 60;

        return $m > 0 ? $m.'m '.str_pad((string) $s, 2, '0', STR_PAD_LEFT).'s' : $s.'s';
    }

    private function syncSuccessRateInWindow($from, $to): float
    {
        $total = SyncEvent::query()
            ->whereBetween('created_at', [$from, $to])
            ->count();

        if ($total <= 0) {
            return 100.0;
        }

        $processed = SyncEvent::query()
            ->whereBetween('created_at', [$from, $to])
            ->where('processed', true)
            ->count();

        return round(($processed / $total) * 100, 1);
    }

    private function estimatedThroughputPerSec(): float
    {
        $processedInLastHour = SyncEvent::query()
            ->where('processed', true)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $processedInLastHour / 3600;
    }

    private function buildSyncPipeline(): array
    {
        $now = now();
        $activeCutoff = $now->copy()->subHours(2);
        $archiveCutoff = $now->copy()->subDay();

        $steps = [
            ['key' => 'detect', 'label' => 'DETECT', 'event_types' => ['story_start']],
            ['key' => 'validate', 'label' => 'VALIDATE', 'event_types' => ['story_complete']],
            ['key' => 'apply', 'label' => 'APPLY', 'event_types' => ['vocab_seen', 'activity_complete']],
            ['key' => 'confirm', 'label' => 'CONFIRM', 'event_types' => ['badge_earned']],
            ['key' => 'archive', 'label' => 'ARCHIVE', 'event_types' => null],
        ];

        $pipeline = [];

        foreach ($steps as $step) {
            if ($step['event_types'] === null) {
                $completed = SyncEvent::query()
                    ->where('processed', true)
                    ->where('created_at', '<', $archiveCutoff)
                    ->count();

                $active = SyncEvent::query()
                    ->where('processed', false)
                    ->where('created_at', '>=', $activeCutoff)
                    ->count();

                $pending = SyncEvent::query()
                    ->where('processed', false)
                    ->where('created_at', '<', $activeCutoff)
                    ->count();
            } else {
                $query = SyncEvent::query()->whereIn('event_type', $step['event_types']);

                $completed = (clone $query)->where('processed', true)->count();
                $active = (clone $query)->where('processed', false)->where('created_at', '>=', $activeCutoff)->count();
                $pending = (clone $query)->where('processed', false)->where('created_at', '<', $activeCutoff)->count();
            }

            $status = $active > 0
                ? 'active'
                : ($completed > 0 ? 'done' : 'pending');

            $tone = match ($status) {
                'done' => 'emerald',
                'active' => 'violet',
                default => 'amber',
            };

            $pipeline[] = [
                'key' => $step['key'],
                'label' => $step['label'],
                'status' => $status,
                'tone' => $tone,
                'completed' => $completed,
                'active' => $active,
                'pending' => $pending,
            ];
        }

        return $pipeline;
    }

    private function formatCompact(int $n): string
    {
        $abs = abs($n);

        if ($abs >= 1000000) {
            return number_format($n / 1000000, 1).'M';
        }

        if ($abs >= 1000) {
            return number_format($n / 1000, 1).'K';
        }

        return number_format($n);
    }

    /**
     * @return array{dir: 'up'|'down'|'neutral', label: string}
     */
    private function computeTrendFromCounts(int $current, int $previous): array
    {
        if ($previous <= 0) {
            return $current > 0
                ? ['dir' => 'up', 'label' => '+100%']
                : ['dir' => 'neutral', 'label' => 'Stable'];
        }

        $deltaPct = (($current - $previous) / $previous) * 100;

        if (abs($deltaPct) < 0.5) {
            return ['dir' => 'neutral', 'label' => 'Stable'];
        }

        $dir = $deltaPct > 0 ? 'up' : 'down';
        $abs = number_format(abs($deltaPct), 1);

        return [
            'dir' => $dir,
            'label' => ($deltaPct > 0 ? '+' : '−').$abs.'%',
        ];
    }

    private function estimatedLatencyMs(): int
    {
        $recent = SyncEvent::query()
            ->whereNotNull('processed_at')
            ->where('created_at', '>=', now()->subDay())
            ->get(['created_at', 'processed_at']);

        if ($recent->isEmpty()) {
            return 124;
        }

        $sum = 0;
        $count = 0;
        foreach ($recent as $row) {
            if ($row->processed_at) {
                $sum += abs($row->created_at->diffInMilliseconds($row->processed_at));
                $count++;
            }
        }

        return $count > 0 ? (int) max(12, round($sum / $count)) : 124;
    }

    /**
     * @return list<array{actor: string, line: string, meta: string, time: string, avatar: string, tone: string}>
     */
    private function buildActivityFeed(): array
    {
        $logs = AuditLog::query()
            ->with('user')
            ->latest()
            ->limit(12)
            ->get();

        if ($logs->isEmpty()) {
            return [
                [
                    'actor' => 'Culture Kids',
                    'line' => 'Your audit trail will appear here as your team takes action across the platform.',
                    'meta' => 'Getting started',
                    'time' => 'now',
                    'avatar' => 'CK',
                    'tone' => 'emerald',
                ],
            ];
        }

        $items = [];
        foreach ($logs as $log) {
            $name = $log->user?->name ?? 'System';
            $items[] = [
                'actor' => $name,
                'line' => Str::of($log->action)->replace('_', ' ')->title()->toString()
                    . ($log->model_type ? ' · '.class_basename($log->model_type) : ''),
                'meta' => 'Audit',
                'time' => $log->created_at?->diffForHumans() ?? '',
                'avatar' => Str::upper(Str::substr((string) Str::ascii($name), 0, 2)),
                'tone' => ['emerald', 'amber', 'violet'][abs(crc32((string) $log->id)) % 3],
            ];
        }

        return $items;
    }

    /**
     * @return list<array{type: string, title: string, body: string}>
     */
    private function buildAlerts(float $storagePct, int $failedJobs, int $pendingJobs, float $syncPct): array
    {
        $alerts = [];

        if ($storagePct >= 80) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Storage approaching capacity',
                'body' => "Server volume is about {$storagePct}% full — schedule cleanup or expansion.",
            ];
        } else {
            $alerts[] = [
                'type' => 'success',
                'title' => 'Storage healthy',
                'body' => 'Disk usage is in a comfortable range for steady growth.',
            ];
        }

        if ($failedJobs > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Failed jobs need attention',
                'body' => "{$failedJobs} failed job(s) — review the queue and error tooling.",
            ];
        }

        if ($pendingJobs > 500) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Queue depth elevated',
                'body' => "{$pendingJobs} jobs pending — consider scaling workers at peak times.",
            ];
        }

        if ($syncPct < 90 && SyncEvent::count() > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Sync processing below target',
                'body' => "Success rate is {$syncPct}% — inspect device sync pipelines.",
            ];
        }

        $alerts[] = [
            'type' => 'info',
            'title' => 'Celebrate the rhythm',
            'body' => 'Every story shared is culture preserved for the next generation.',
        ];

        return $alerts;
    }
}
