<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PremiumDashboardWidget;
use BackedEnum;
use Filament\Pages\Dashboard as FilamentDashboard;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Dashboard extends FilamentDashboard
{
    protected Width|string|null $maxContentWidth = Width::Full;

    protected static ?string $title = 'Global Dashboard';

    protected static ?string $navigationLabel = 'Global Dashboard';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static UnitEnum|string|null $navigationGroup = 'PLATFORM';

    protected static ?int $navigationSort = 1;

    public function getHeading(): string | Htmlable | null
    {
        return null;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }

    /**
     * @return array<class-string<\Filament\Widgets\Widget> | \Filament\Widgets\WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            PremiumDashboardWidget::class,
        ];
    }

    /**
     * @return array<class-string<\Filament\Widgets\Widget> | \Filament\Widgets\WidgetConfiguration>
     */
    protected function getHeaderWidgets(): array
    {
        return [];
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int | array
    {
        return 1;
    }
}
