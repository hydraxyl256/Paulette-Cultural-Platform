<?php

namespace App\Filament\Resources;

use App\Models\Comic;
use Filament\Forms;
use Filament\Forms\Form;
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
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\ComicResource\Pages;

class ComicResource extends Resource
{
    protected static ?string $model = Comic::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 1;
    protected static UnitEnum|string|null $navigationGroup = 'CONTENT';
    protected static ?string $navigationLabel = 'Comics CMS';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Tabs::make('Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Content')
                        ->icon('heroicon-o-book-open')
                        ->schema([
                            Forms\Components\Section::make('Comic Information')
                                ->description('Core content details')
                                ->columns(2)
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label('Comic Title')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(2)
                                        ->placeholder('E.g., The Great Ancestor\'s Journey'),
                                    Forms\Components\Select::make('tribe_id')
                                        ->label('Cultural Tribe')
                                        ->relationship('tribe', 'name')
                                        ->required()
                                        ->native(false)
                                        ->searchable()
                                        ->helperText('Select the tribe this comic features'),
                                    Forms\Components\Select::make('org_id')
                                        ->label('Organisation')
                                        ->relationship('organisation', 'name')
                                        ->required()
                                        ->native(false)
                                        ->searchable(),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Settings')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Forms\Components\Section::make('Content Configuration')
                                ->description('Age range and content settings')
                                ->columns(2)
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('age_min')
                                        ->label('Recommended Age (Min)')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(18)
                                        ->suffix(' years'),
                                    Forms\Components\TextInput::make('age_max')
                                        ->label('Recommended Age (Max)')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(18)
                                        ->suffix(' years'),
                                    Forms\Components\Select::make('status')
                                        ->label('Publication Status')
                                        ->options([
                                            'draft' => 'Draft (Editing)',
                                            'review' => 'In Review (Pending)',
                                            'published' => 'Published',
                                            'archived' => 'Archived',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->default('draft')
                                        ->columnSpan(2),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make('Media Assets')
                                ->description('Upload cover image and content files')
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\FileUpload::make('cover_image_path')
                                        ->label('Cover Image')
                                        ->image()
                                        ->directory('comics/covers')
                                        ->maxSize(5120)
                                        ->imageResizeMode('cover')
                                        ->imageCropAspectRatio('2/3')
                                        ->helperText('Recommended: 800x1200px, aspect ratio 2:3 (portrait)'),
                                    Forms\Components\FileUpload::make('bundle_path')
                                        ->label('Offline Bundle (ZIP)')
                                        ->directory('comics/bundles')
                                        ->maxSize(51200)
                                        ->acceptedFileTypes(['application/zip'])
                                        ->helperText('ZIP bundle for offline access (~20-50MB typically)'),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Meta')
                        ->icon('heroicon-o-info-circle')
                        ->schema([
                            Forms\Components\Section::make('Bundle Information')
                                ->description('Integrity and versioning')
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('bundle_hash')
                                        ->label('Bundle Hash (SHA256)')
                                        ->disabled()
                                        ->helperText('Auto-generated when bundle is uploaded'),
                                    
                                    Forms\Components\Placeholder::make('panels_count')
                                        ->label('Total Pages/Panels')
                                        ->content(fn (Comic $record) => $record?->panels()->count() ?? 0),
                                ]),
                        ]),
                ])->activeTab(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image_path')
                    ->label('')
                    ->height('auto')
                    ->width(60),

                TextColumn::make('title')
                    ->label('Comic')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-s-book-open')
                    ->iconPosition('before')
                    ->description(fn (Comic $record) => $record->tribe->name . ' • Ages ' . $record->age_min . '-' . $record->age_max),

                TextColumn::make('tribe.name')
                    ->label('Tribe')
                    ->searchable()
                    ->sortable()
                    ->hidden(),

                TextColumn::make('organisation.name')
                    ->label('Organisation')
                    ->searchable()
                    ->hidden(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'draft'     => 'Draft',
                        'review'    => 'In Review',
                        'published' => 'Published',
                        'archived'  => 'Archived',
                        default     => ucfirst($state),
                    })
                    ->color(fn ($state) => match($state) {
                        'draft'     => 'gray',
                        'review'    => 'warning',
                        'published' => 'success',
                        'archived'  => 'danger',
                        default     => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->hidden(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tribe_id')
                    ->label('Tribe')
                    ->relationship('tribe', 'name')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->multiple()
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'In Review',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('org_id')
                    ->label('Organisation')
                    ->relationship('organisation', 'name')
                    ->hidden(),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-s-pencil-square'),
                Action::make('publish')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->visible(fn (Comic $record) => $record->status !== 'published')
                    ->requiresConfirmation()
                    ->modalHeading('Publish Comic?')
                    ->modalDescription('This will make the comic available to all users. Changes cannot be made after publishing.')
                    ->modalSubmitActionLabel('Yes, Publish')
                    ->action(function (Comic $record) {
                        $record->update(['status' => 'published']);
                        Notification::make()->success()->title('Comic Published')
                            ->body("{$record->title} is now live")->send();
                    }),
                Action::make('archive')
                    ->icon('heroicon-s-archive-box')
                    ->color('warning')
                    ->visible(fn (Comic $record) => $record->status === 'published')
                    ->requiresConfirmation()
                    ->modalHeading('Archive Comic?')
                    ->modalDescription('Users will no longer be able to access this comic.')
                    ->modalSubmitActionLabel('Yes, Archive')
                    ->action(function (Comic $record) {
                        $record->update(['status' => 'archived']);
                        Notification::make()->warning()->title('Comic Archived')
                            ->body("{$record->title} is no longer available")->send();
                    }),
                DeleteAction::make()
                    ->icon('heroicon-s-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
        return Auth::user()->hasRole('super_admin') && $record->status !== 'published';
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComics::route('/'),
            'create' => Pages\CreateComic::route('/create'),
            'edit' => Pages\EditComic::route('/{record}/edit'),
        ];
    }
}
