<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ApiTokens extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-key';

    protected static UnitEnum|string|null $navigationGroup = 'SYSTEM';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'API Tokens';

    protected static ?string $navigationLabel = 'API Tokens';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('API access')
                    ->description('Issue and revoke Sanctum tokens for trusted integrations and automation.')
                    ->components([
                        Text::make('Token minting, scopes, and rotation policies will be configurable from this screen.'),
                    ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }
}
