<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PanelSettings extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Settings';

    protected static ?string $navigationLabel = 'Settings';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Super Admin settings')
                    ->description('Panel preferences, notifications, and operational toggles.')
                    ->components([
                        Text::make('Global Filament preferences, maintenance banners, and integration keys will be centralised here.'),
                    ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }
}
