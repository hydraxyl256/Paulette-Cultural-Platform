<?php

namespace App\Filament\Resources;

use App\Models\Organisation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\OrganisationResource\Pages;

class OrganisationResource extends Resource
{
    protected static ?string $model = Organisation::class;
    // Avoid colliding with legacy Laravel routes in routes/web.php:
    // GET /admin/organisations
    protected static ?string $slug = 'organisations-management';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 2;
    protected static UnitEnum|string|null $navigationGroup = 'PLATFORM';
    protected static ?string $navigationLabel = 'Organisations';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Tabs::make('Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Organization')
                        ->icon('heroicon-o-building-office-2')
                        ->schema([
                            Forms\Components\Section::make('Organisation Details')
                                ->description('Core information about this educational partnership')
                                ->columns(2)
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Organisation Name')
                                        ->required()
                                        ->maxLength(255)
                                        ->placeholder('e.g., Lagos Heritage Academy')
                                        ->columnSpan(2)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                                            if (!filled($set('slug'))) {
                                                $set('slug', str($state)->slug());
                                            }
                                        }),
                                    Forms\Components\TextInput::make('slug')
                                        ->label('URL Slug')
                                        ->required()
                                        ->unique(ignorable: fn ($record) => $record)
                                        ->maxLength(255)
                                        ->placeholder('lagos-heritage-academy')
                                        ->helperText('Auto-generated from name, used in URLs'),
                                    Forms\Components\Select::make('plan')
                                        ->label('Subscription Plan')
                                        ->options([
                                            'community' => 'Community (Free)',
                                            'standard' => 'Standard',
                                            'premium' => 'Premium Curator',
                                            'enterprise' => 'Enterprise Plus',
                                        ])
                                        ->descriptions([
                                            'community' => 'Limited features, perfect for testing',
                                            'standard' => 'Core modules + analytics',
                                            'premium' => 'Everything + priority support',
                                            'enterprise' => 'Custom features + dedicated manager',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->helperText('Controls feature limits, analytics depth, and support level.'),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Modules')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            Forms\Components\Section::make('Module Configuration')
                                ->description('Enable/disable content modules for this organisation')
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\CheckboxList::make('modules')
                                        ->label('Available Modules')
                                        ->options([
                                            'comics' => 'Comics & Stories',
                                            'songs' => 'Songs & Audio',
                                            'flashcards' => 'Flashcards',
                                            'assessments' => 'Assessments',
                                            'badges' => 'Achievements & Badges',
                                            'analytics' => 'Advanced Analytics',
                                            'api' => 'API Access',
                                        ])
                                        ->descriptions([
                                            'comics' => 'Cultural storytelling content',
                                            'songs' => 'Music and audio lessons',
                                            'flashcards' => 'Vocabulary and language tools',
                                            'assessments' => 'Quizzes and evaluations',
                                            'badges' => 'Gamification rewards',
                                            'analytics' => 'Deep reporting & insights',
                                            'api' => 'Third-party integrations',
                                        ])
                                        ->columns(2)
                                        ->gridDirection('row')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Branding')
                        ->icon('heroicon-o-swatch')
                        ->schema([
                            Forms\Components\Section::make('Theme Customisation')
                                ->description('Customise the look & feel for this organisation')
                                ->collapsible(false)
                                ->columns(2)
                                ->schema([
                                    Forms\Components\ColorPicker::make('theme_config.primary_color')
                                        ->label('Primary Brand Color')
                                        ->default('#0f9361')
                                        ->helperText('Main CTA and accent color'),
                                    Forms\Components\ColorPicker::make('theme_config.secondary_color')
                                        ->label('Secondary Color')
                                        ->default('#d67800')
                                        ->helperText('Highlights and warnings'),
                                    Forms\Components\TextInput::make('theme_config.accent_emoji')
                                        ->label('Brand Emoji/Symbol')
                                        ->maxLength(10)
                                        ->placeholder('🎭')
                                        ->helperText('Single emoji for visual branding'),
                                    Forms\Components\TextInput::make('theme_config.logo_url')
                                        ->label('Logo URL')
                                        ->url()
                                        ->placeholder('https://example.com/logo.png')
                                        ->helperText('Optional: Full logo image'),
                                    Forms\Components\Textarea::make('theme_config.custom_css')
                                        ->label('Custom CSS')
                                        ->columnSpan(2)
                                        ->placeholder('.custom-class { /* your styles */ }')
                                        ->helperText('Advanced: Custom CSS overrides')
                                        ->rows(8),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Status')
                        ->icon('heroicon-o-signal')
                        ->schema([
                            Forms\Components\Section::make('Organisation Status')
                                ->description('Control access and operational settings')
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Active')
                                        ->inline()
                                        ->default(true)
                                        ->helperText('Inactive organisations cannot be accessed by users'),
                                    
                                    Forms\Components\Fieldset::make('Status Information')
                                        ->relationship(false)
                                        ->schema([
                                            Forms\Components\Placeholder::make('member_count')
                                                ->label('Total Members')
                                                ->content(fn ($record) => $record?->users()->count() ?? 0),
                                            
                                            Forms\Components\Placeholder::make('children_count')
                                                ->label('Active Children')
                                                ->content(fn ($record) => $record?->childProfiles()->where('status', 'active')->count() ?? 0),
                                            
                                            Forms\Components\Placeholder::make('created_date')
                                                ->label('Created')
                                                ->content(fn ($record) => $record?->created_at?->format('M d, Y') ?? 'N/A'),
                                        ])
                                        ->columns(3),
                                ]),
                        ]),
                ])->activeTab(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Organisation')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Organisation $record) => $record->slug),

                TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'free' => 'Community',
                        'school' => 'Standard Edu',
                        'enterprise' => 'Enterprise Global',
                        default => ucfirst($state),
                    })
                    ->color(fn ($state) => match($state) {
                        'enterprise' => 'success',
                        'school' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('users_count')
                    ->label('Users')
                    ->getStateUsing(fn (Organisation $record) => $record->users()->count())
                    ->sortable(),

                TextColumn::make('childProfiles_count')
                    ->label('Children')
                    ->getStateUsing(fn (Organisation $record) => $record->childProfiles()->count())
                    ->sortable(),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Suspended')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('plan')
                    ->label('Subscription Plan')
                    ->multiple()
                    ->options([
                        'community' => 'Community',
                        'standard' => 'Standard',
                        'premium' => 'Premium Curator',
                        'enterprise' => 'Enterprise Plus',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All Orgs')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-s-pencil-square')
                    ->label('Edit'),
                Action::make('suspend')
                    ->icon('heroicon-s-pause-circle')
                    ->color('warning')
                    ->label('Suspend')
                    ->visible(fn (Organisation $record) => $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Suspend Organisation?')
                    ->modalDescription('This organisation will be unable to access the platform.')
                    ->modalSubmitActionLabel('Yes, Suspend')
                    ->action(fn (Organisation $record) => $record->update(['is_active' => false])),
                Action::make('activate')
                    ->icon('heroicon-s-play-circle')
                    ->color('success')
                    ->label('Activate')
                    ->visible(fn (Organisation $record) => !$record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Activate Organisation?')
                    ->modalDescription('This organisation will regain access to all features.')
                    ->modalSubmitActionLabel('Yes, Activate')
                    ->action(fn (Organisation $record) => $record->update(['is_active' => true])),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_activate')
                        ->label('Activate selected')
                        ->color('success')
                        ->icon('heroicon-s-play-circle')
                        ->requiresConfirmation()
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => true])),
                    BulkAction::make('bulk_suspend')
                        ->label('Suspend selected')
                        ->color('warning')
                        ->icon('heroicon-s-pause-circle')
                        ->requiresConfirmation()
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => false])),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->poll('5s')
            ->emptyStateHeading('No organisations yet')
            ->emptyStateDescription('Once you connect your first school or partner, their health will appear here.')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\TextEntry::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'enterprise' => 'success',
                        'school' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'free' => 'Community',
                        'school' => 'Standard Edu',
                        'enterprise' => 'Enterprise Global',
                        default => ucfirst($state ?? ''),
                    }),
    
                \Filament\Infolists\Components\TextEntry::make('name')
                    ->label('Organisation')
                    ->weight('bold'),
    
                \Filament\Infolists\Components\TextEntry::make('slug')
                    ->label('Slug'),
    
                \Filament\Infolists\Components\TextEntry::make('modules')
                    ->label('Modules')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
    
                \Filament\Infolists\Components\TextEntry::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Suspended')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
    
                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i'),
            ]);
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
            'index' => Pages\ListOrganisations::route('/'),
            'create' => Pages\CreateOrganisation::route('/create'),
            'edit' => Pages\EditOrganisation::route('/{record}/edit'),
        ];
    }
}
