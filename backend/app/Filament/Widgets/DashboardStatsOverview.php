<?php

namespace App\Filament\Widgets;

use App\Models\ChildProfile;
use App\Models\Comic;
use App\Models\Organisation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Children', ChildProfile::where('status', 'active')->count())
                ->description('Engaged learners')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Organisations', Organisation::count())
                ->description('Active networks')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([5, 2, 8, 3, 6, 2, 4, 5]),

            Stat::make('Comics Published', Comic::where('status', 'published')->count())
                ->description('In library')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([12, 10, 14, 15, 13, 16, 15, 18]),
        ];
    }
}
