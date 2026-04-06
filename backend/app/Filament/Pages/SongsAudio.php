<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class SongsAudio extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-musical-note';

    protected static UnitEnum|string|null $navigationGroup = 'CONTENT';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Songs & Audio';

    protected static ?string $navigationLabel = 'Songs & Audio';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Songs & Audio')
                    ->description('Curate tracks, narration, and audio learning packs for the mobile experience.')
                    ->components([
                        Text::make('Full CMS tools for uploads, metadata, and publishing workflows will appear here as the content pipeline is connected.'),
                    ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }
}
