<?php

namespace App\Filament\Resources;

use App\Models\AgeProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\AgeProfileResource\Pages;

class AgeProfileResource extends Resource
{
    protected static ?string $model = AgeProfile::class;
    // Avoid colliding with existing legacy Laravel routes:
    // routes/web.php defines GET/PUT admin/age-profiles, which prevents Filament from registering
    // the index route at the same URI.
    protected static ?string $slug = 'age-profiles-management';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calculator';
    protected static ?int $navigationSort = 2;
    protected static UnitEnum|string|null $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel = 'Age Profiles';
    protected static ?string $recordTitleAttribute = 'stage';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Tabs::make('Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Age Group')
                        ->icon('heroicon-o-cake')
                        ->schema([
                            Forms\Components\Section::make('Age Range Definition')
                                ->description('Define the age range and developmental stage')
                                ->columns(2)
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('age_min')
                                        ->label('Minimum Age')
                                        ->numeric()
                                        ->required()
                                        ->minValue(0)
                                        ->maxValue(18)
                                        ->suffix(' years'),
                                    Forms\Components\TextInput::make('age_max')
                                        ->label('Maximum Age')
                                        ->numeric()
                                        ->required()
                                        ->minValue(0)
                                        ->maxValue(18)
                                        ->suffix(' years'),
                                    Forms\Components\TextInput::make('stage')
                                        ->label('Development Stage')
                                        ->required()
                                        ->maxLength(100)
                                        ->columnSpan(2)
                                        ->placeholder('e.g., Toddler, Preschool, Early School')
                                        ->helperText('Human-readable stage name for this age group'),
                                ]),
                        ]),

                    Forms\Components\Tabs\Tab::make('Interface')
                        ->icon('heroicon-o-swatch')
                        ->schema([
                            Forms\Components\Section::make('UI Customization')
                                ->description('Adapt the interface and interaction for this age group')
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\Select::make('ui_mode')
                                        ->label('UI Design Mode')
                                        ->options([
                                            'simplified' => '👶 Simplified (Large buttons, minimal text)',
                                            'normal' => '👧 Normal (Balanced interface)',
                                            'advanced' => '👦 Advanced (Full features enabled)',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->default('normal')
                                        ->helperText('Determines visual style and interaction complexity'),
                                ]),
                        ]),

                    Forms\Components\Tabs\Tab::make('Learning')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            Forms\Components\Section::make('Content & Difficulty')
                                ->description('Configure content difficulty and learning parameters')
                                ->columns(2)
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('difficulty_ceiling')
                                        ->label('Max Difficulty Level')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(10)
                                        ->helperText('Content difficulty ceiling (1-10 scale)'),
                                ]),

                            Forms\Components\Section::make('Rules (Advanced)')
                                ->description('Define custom behavior and restrictions as JSON')
                                ->collapsible()
                                ->collapsed()
                                ->schema([
                                    Forms\Components\Textarea::make('rules')
                                        ->label('Rules Configuration')
                                        ->helperText('Advanced: JSON key-value pairs for runtime rules')
                                        ->rows(8)
                                        ->json()
                                        ->default('{}')
                                        ->columnSpan('full'),
                                ]),
                        ]),
                ])->activeTab(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stage')
                    ->label('Development Stage')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-s-cake')
                    ->iconPosition('before'),

                TextColumn::make('age_min')
                    ->label('Age Range')
                    ->getStateUsing(fn (AgeProfile $record) => "{$record->age_min}–{$record->age_max} years")
                    ->sortable(),

                BadgeColumn::make('ui_mode')
                    ->label('UI Mode')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'simplified' => '👶 Simplified',
                        'normal' => '👧 Normal',
                        'advanced' => '👦 Advanced',
                        default => ucfirst($state),
                    })
                    ->colors([
                        'info' => 'simplified',
                        'success' => 'normal',
                        'warning' => 'advanced',
                    ]),

                TextColumn::make('difficulty_ceiling')
                    ->label('Max Difficulty')
                    ->formatStateUsing(fn ($state) => "Level $state")
                    ->icon('heroicon-s-signal')
                    ->iconPosition('before')
                    ->sortable(),

                TextColumn::make('childProfiles_count')
                    ->label('Child Profiles')
                    ->getStateUsing(fn (AgeProfile $record) => $record->childProfiles()->count())
                    ->formatStateUsing(fn ($state) => "{$state} profile" . ($state !== 1 ? 's' : ''))
                    ->icon('heroicon-s-users')
                    ->iconPosition('before')
                    ->color('emerald'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->hidden(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ui_mode')
                    ->label('UI Mode')
                    ->multiple()
                    ->options([
                        'simplified' => 'Simplified',
                        'normal' => 'Normal',
                        'advanced' => 'Advanced',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-s-pencil-square'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-s-trash')
                    ->visible(fn (AgeProfile $record) => $record->childProfiles()->count() === 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
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
            'index' => Pages\ListAgeProfiles::route('/'),
            'create' => Pages\CreateAgeProfile::route('/create'),
            'edit' => Pages\EditAgeProfile::route('/{record}/edit'),
        ];
    }
}