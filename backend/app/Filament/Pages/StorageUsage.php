<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BackedEnum;
use UnitEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StorageUsage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-server-stack';
    protected static UnitEnum|string|null $navigationGroup = 'SYSTEM';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = 'Storage Usage';

    public function getStorageStats(): array
    {
        $disks = [
            'app' => $this->getDiskUsage(''),
            'comics' => $this->getDiskUsage('comics'),
            'bundles' => $this->getDiskUsage('bundles'),
            'logs' => $this->getLogsSize(),
            'cache' => $this->getCacheSize(),
        ];

        $total = array_sum(array_map(fn ($d) => $d['bytes'], $disks));
        $totalLimit = $this->getTotalStorageLimit();
        $usage_percent = $totalLimit > 0 ? round(($total / $totalLimit) * 100, 1) : 0;

        return [
            'disks' => $disks,
            'total_bytes' => $total,
            'total_formatted' => $this->formatBytes($total),
            'total_limit' => $totalLimit,
            'total_limit_formatted' => $this->formatBytes($totalLimit),
            'usage_percent' => $usage_percent,
            'status' => match(true) {
                $usage_percent > 90 => 'danger',
                $usage_percent > 70 => 'warning',
                default => 'success',
            },
        ];
    }

    private function getDiskUsage(string $path = ''): array
    {
        $disk = Storage::disk('local');
        $path = $path ?: '/';

        // Simple size calculation
        $size = 0;
        $files = $disk->allFiles($path);

        foreach ($files as $file) {
            $size += $disk->size($file);
        }

        return [
            'bytes' => $size,
            'formatted' => $this->formatBytes($size),
            'files' => count($files),
        ];
    }

    private function getLogsSize(): array
    {
        $logsPath = storage_path('logs');

        if (!is_dir($logsPath)) {
            return ['bytes' => 0, 'formatted' => '0 B', 'files' => 0];
        }

        $size = 0;
        $files = array_diff(scandir($logsPath), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $logsPath . '/' . $file;
            if (is_file($filePath)) {
                $size += filesize($filePath);
            }
        }

        return [
            'bytes' => $size,
            'formatted' => $this->formatBytes($size),
            'files' => count($files),
        ];
    }

    private function getCacheSize(): array
    {
        $cachePath = storage_path('framework/cache');

        if (!is_dir($cachePath)) {
            return ['bytes' => 0, 'formatted' => '0 B', 'files' => 0];
        }

        $size = 0;
        $files = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cachePath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
                $files++;
            }
        }

        return [
            'bytes' => $size,
            'formatted' => $this->formatBytes($size),
            'files' => $files,
        ];
    }

    private function getTotalStorageLimit(): int
    {
        // Return total storage limit in bytes (e.g., 500GB)
        return 500 * 1024 * 1024 * 1024; // 500GB
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }
}
