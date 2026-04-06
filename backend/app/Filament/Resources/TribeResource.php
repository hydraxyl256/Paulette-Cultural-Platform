<?php

namespace App\Filament\Resources;

use App\Models\Tribe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\TribeResource\Pages;

class TribeResource extends Resource
{
    protected static ?string $model = Tribe::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?int $navigationSort = 4;
    protected static UnitEnum|string|null $navigationGroup = 'PLATFORM';
    protected static ?string $navigationLabel = 'Tribes';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Grid::make()
                ->columns([
                    'default' => 1,
                    'md' => 2,
                ])
                ->schema([
                    Forms\Components\Tabs::make('Tabs')
                        ->columnSpan(1)
                        ->tabs([
                            Forms\Components\Tabs\Tab::make('Identity')
                                ->icon('heroicon-o-sparkles')
                                ->schema([
                                    Forms\Components\Section::make('Tribe Identity')
                                        ->description('Define the cultural identity of this tribe')
                                        ->columns(2)
                                        ->collapsible(false)
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->label('Tribe Name')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (Forms\Set $set, ?string $state) =>
                                                    filled($state) ? $set('slug', str($state)->slug()) : null
                                                )
                                                ->columnSpan(2),
                                            Forms\Components\TextInput::make('slug')
                                                ->label('URL Slug')
                                                ->required()
                                                ->unique(ignorable: fn ($record) => $record)
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('emoji_symbol')
                                                ->label('Emoji/Symbol')
                                                ->maxLength(10)
                                                ->placeholder('🦁')
                                                ->extraInputAttributes([
                                                    'class' => 'text-3xl text-center font-headline font-bold tracking-tight leading-none',
                                                ])
                                                ->helperText('Single emoji or cultural symbol (e.g., 🦁, 🥁, 🌍)'),
                                        ]),
                                ]),
                            
                            Forms\Components\Tabs\Tab::make('Cultural')
                                ->icon('heroicon-o-globe-alt')
                                ->schema([
                                    Forms\Components\Section::make('Cultural Context')
                                        ->description('Language, region, and cultural information')
                                        ->columns(2)
                                        ->collapsible(false)
                                        ->schema([
                                            Forms\Components\TextInput::make('language')
                                                ->label('Primary Language')
                                                ->maxLength(100)
                                                ->placeholder('Kiswahili')
                                                ->helperText('e.g., Kiswahili, Amharic, Twi'),
                                            Forms\Components\TextInput::make('region')
                                                ->label('Geographic Region')
                                                ->maxLength(100)
                                                ->placeholder('East Africa')
                                                ->helperText('e.g., East Africa, West Africa, Southern Africa'),
                                            Forms\Components\TextInput::make('greeting')
                                                ->label('Traditional Greeting')
                                                ->maxLength(255)
                                                ->placeholder('Jambo')
                                                ->columnSpan(1)
                                                ->helperText('e.g., \"Jambo\" (Swahili)'),
                                            Forms\Components\TextInput::make('phonetic')
                                                ->label('Phonetic Pronunciation')
                                                ->maxLength(255)
                                                ->placeholder('JAM-bo')
                                                ->columnSpan(1)
                                                ->helperText('How to pronounce the greeting'),
                                        ]),
                                ]),
                            
                            Forms\Components\Tabs\Tab::make('Branding')
                                ->icon('heroicon-o-swatch')
                                ->schema([
                                    Forms\Components\Section::make('Visual Identity')
                                        ->description('Colors and branding')
                                        ->collapsible(false)
                                        ->schema([
                                            Forms\Components\ColorPicker::make('color_hex')
                                                ->label('Primary Brand Color')
                                                ->required()
                                                ->helperText('Used in UI elements and branding'),
                                        ]),
                                ]),
                            
                            Forms\Components\Tabs\Tab::make('Status')
                                ->icon('heroicon-o-signal')
                                ->schema([
                                    Forms\Components\Section::make('Availability')
                                        ->description('Control visibility and access')
                                        ->collapsible(false)
                                        ->schema([
                                            Forms\Components\Toggle::make('is_active')
                                                ->label('Active')
                                                ->inline()
                                                ->default(true)
                                                ->helperText('Inactive tribes will not appear in content listings'),
                                            
                                            Forms\Components\Placeholder::make('comics_count')
                                                ->label('Associated Comics')
                                                ->content(fn (Tribe $record) => $record?->comics()->count() ?? 0),
                                        ]),
                                ]),
                        ])->activeTab(1),

                    Forms\Components\Section::make('Live Preview')
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\Placeholder::make('preview')
                                ->label('')
                                ->content(function (Forms\Get $get) {
                                    $emoji = $get('emoji_symbol') ?: '🌍';
                                    $name = $get('name') ?: 'Your Tribe Name';
                                    $greeting = $get('greeting') ?: 'Traditional greeting will appear here';
                                    $region = $get('region') ?: 'Region';
                                    $language = $get('language') ?: 'Language';
                                    $color = $get('color_hex') ?: '#0f9361';

                                    $emojiSafe = htmlspecialchars($emoji, ENT_QUOTES, 'UTF-8');
                                    $nameSafe = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                                    $greetingSafe = htmlspecialchars($greeting, ENT_QUOTES, 'UTF-8');
                                    $regionSafe = htmlspecialchars($region, ENT_QUOTES, 'UTF-8');
                                    $languageSafe = htmlspecialchars($language, ENT_QUOTES, 'UTF-8');

                                    $bg = htmlspecialchars($color, ENT_QUOTES, 'UTF-8');
                                    $bgAlphaA = htmlspecialchars($color . '33', ENT_QUOTES, 'UTF-8'); // 20% opacity-ish
                                    $bgAlphaB = htmlspecialchars($color . '10', ENT_QUOTES, 'UTF-8'); // ~6% opacity-ish

                                    return new HtmlString(<<<HTML
<div class="rounded-[24px] border border-[rgba(202,201,216,0.25)] bg-[rgba(255,248,255,0.82)] shadow-[0_4px_20px_rgba(19,27,46,0.12)] backdrop-blur-[24px] p-4"
     style="background: radial-gradient(circle at top left, {$bgAlphaA} 0%, rgba(255,248,255,0.86) 55%, {$bgAlphaB} 100%);">
    <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-[rgba(19,27,46,0.65)]">Child app preview</p>
    <div class="flex items-center gap-4 rounded-[20px] bg-[rgba(19,27,46,0.02)] p-3">
        <div class="flex h-14 w-14 items-center justify-center rounded-[18px] text-3xl"
             style="background: linear-gradient(135deg, {$bg} 0%, rgba(39,211,132,0.95) 100%);">
            <span>{$emojiSafe}</span>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-[var(--color-on-surface)]">{$nameSafe}</p>
            <p class="text-xs text-[var(--color-on-surface)]/70 truncate">{$regionSafe} · {$languageSafe}</p>
            <p class="mt-1 text-xs text-[var(--color-on-surface)]/60 truncate">“{$greetingSafe}”</p>
        </div>
    </div>
</div>
HTML);
                                }),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('emoji_symbol')
                    ->label('')
                    ->size('xl')
                    ->width('72px')
                    ->alignment('center')
                    ->formatStateUsing(fn ($state) => $state ?: '🌍'),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ,

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('language')
                    ->label('Language')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('region')
                    ->label('Region')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('color_hex')
                    ->label('Color')
                    ->formatStateUsing(function ($state) {
                        $hex = $state ?: '#0f9361';
                        $safe = htmlspecialchars($hex, ENT_QUOTES, 'UTF-8');
                        return new HtmlString("<div class='flex items-center gap-2'><span class='h-4 w-4 rounded-full border border-white/40 shadow-sm' style='background-color: {$safe}'></span><span class='text-xs font-mono text-[var(--color-on-surface)]/70'>{$safe}</span></div>");
                    })
                    ->html(),

                TextColumn::make('greeting')
                    ->label('Greeting')
                    ->limit(28)
                    ->wrap()
                    ->formatStateUsing(fn ($state) => $state ?: '—'),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region')
                    ->label('Region')
                    ->multiple()
                    ->options(fn () => Tribe::active()->pluck('region', 'region')->unique()->toArray()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-s-pencil-square'),
                DeleteAction::make()
                    ->icon('heroicon-s-trash'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->recordClasses('transform-gpu transition duration-150 ease-out hover:scale-[1.01]')
            ->poll('5s');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTribes::route('/'),
            'create' => Pages\CreateTribe::route('/create'),
            'edit' => Pages\EditTribe::route('/{record}/edit'),
        ];
    }
}
