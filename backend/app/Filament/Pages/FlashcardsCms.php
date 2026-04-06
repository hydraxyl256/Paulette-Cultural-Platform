<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class FlashcardsCms extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static UnitEnum|string|null $navigationGroup = 'CONTENT';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Flashcards';

    protected static ?string $navigationLabel = 'Flashcards';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Flashcards')
                    ->description('Build and organise flashcard decks by tribe, age profile, and learning goal.')
                    ->components([
                        Text::make('Flashcard authoring, bulk import, and deck bundling will be available in this workspace.'),
                    ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }
}
