<?php

namespace App\Filament\Resources;

use App\Models\FlashcardDeck;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\FlashcardDeckResource\Pages;

class FlashcardDeckResource extends Resource
{
    protected static ?string $model = FlashcardDeck::class;
    protected static string|\BackedEnum|null  $navigationIcon  = 'heroicon-o-squares-2x2';
    protected static ?int    $navigationSort  = 3;
    protected static string|\UnitEnum|null $navigationGroup = 'CONTENT';
    protected static ?string $navigationLabel = 'Flashcards';
    protected static ?string $recordTitleAttribute = 'name';

    // ── Form ─────────────────────────────────────────────────────
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Deck Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Deck Name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('subtitle')
                        ->label('Subtitle / Topic')
                        ->maxLength(255)
                        ->placeholder('e.g. Vocabulary & Pronunciation')
                        ->columnSpan(2),

                    Forms\Components\Select::make('tribe_id')
                        ->label('Cultural Tribe')
                        ->relationship('tribe', 'name')
                        ->native(false)
                        ->searchable(),

                    Forms\Components\Select::make('org_id')
                        ->label('Organisation')
                        ->relationship('organisation', 'name')
                        ->native(false)
                        ->searchable(),

                    Forms\Components\TextInput::make('age_min')
                        ->label('Min Age')
                        ->numeric()->minValue(1)->maxValue(18)
                        ->suffix('yrs')->default(3),

                    Forms\Components\TextInput::make('age_max')
                        ->label('Max Age')
                        ->numeric()->minValue(1)->maxValue(18)
                        ->suffix('yrs')->default(12),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'live'     => 'Live',
                            'draft'    => 'Draft',
                            'archived' => 'Archived',
                        ])
                        ->native(false)
                        ->default('draft')
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('engagement_rate')
                        ->label('Engagement Rate (× 100)')
                        ->numeric()->default(0)
                        ->helperText('Store as integer × 100. E.g. 84.2% → 8420')
                        ->columnSpan(2),

                    Forms\Components\Toggle::make('is_global')
                        ->label('Global Asset (visible across all orgs)')
                        ->default(false)
                        ->columnSpan(2),
                ]),

            Section::make('Cover Art')
                ->schema([
                    Forms\Components\FileUpload::make('cover_image_path')
                        ->label('Cover Image')
                        ->image()
                        ->directory('flashcards/covers')
                        ->maxSize(3072),
                ]),
        ]);
    }

    // ── Table ────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image_path')->label('')->imageHeight(44)->width(44)->extraImgAttributes(['style' => 'border-radius:50%']),
                TextColumn::make('name')->label('Deck Name')->searchable()->sortable()->weight('bold')
                    ->description(fn (FlashcardDeck $r) => $r->subtitle ?? ''),
                TextColumn::make('tribe.name')->label('Tribe')->sortable()->badge()
                    ->color('primary'),
                TextColumn::make('age_min')->label('Age Group')
                    ->formatStateUsing(fn ($state, FlashcardDeck $r) => $r->age_min . '-' . $r->age_max . ' Yrs'),
                TextColumn::make('cards_count')->counts('cards')->label('Cards')->sortable(),
                TextColumn::make('updated_at')->label('Last Updated')->dateTime('M d, Y')->sortable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($s) => ucfirst($s))
                    ->color(fn ($s) => match ($s) {
                        'live'     => 'success',
                        'draft'    => 'warning',
                        'archived' => 'gray',
                        default    => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['live' => 'Live', 'draft' => 'Draft', 'archived' => 'Archived']),
                Tables\Filters\SelectFilter::make('tribe_id')->label('Tribe')->relationship('tribe', 'name'),
            ])
            ->actions([
                EditAction::make()->icon('heroicon-s-pencil-square'),
                DeleteAction::make()->icon('heroicon-s-trash'),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->striped();
    }

    // ── Pages ────────────────────────────────────────────────────
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFlashcardDecks::route('/'),
            'create' => Pages\CreateFlashcardDeck::route('/create'),
            'edit'   => Pages\EditFlashcardDeck::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return Auth::check(); }
}
