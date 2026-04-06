<?php

namespace App\Filament\Resources;

use App\Models\AudioTrack;
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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\AudioTrackResource\Pages;

class AudioTrackResource extends Resource
{
    protected static ?string $model = AudioTrack::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-musical-note';
    protected static ?int    $navigationSort = 2;
    protected static string|\UnitEnum|null $navigationGroup = 'CONTENT';
    protected static ?string $navigationLabel = 'Songs & Audio';
    protected static ?string $recordTitleAttribute = 'title';

    // ── Form ─────────────────────────────────────────────────────
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Section::make('Track Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Track Title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('subtitle')
                        ->label('Subtitle / Episode')
                        ->maxLength(255)
                        ->placeholder('e.g. Drumming Session #04'),

                    Forms\Components\Select::make('category')
                        ->label('Category')
                        ->options([
                            'yoruba_tribe'    => 'Yoruba Tribe',
                            'igbo_tribe'      => 'Igbo Tribe',
                            'zulu_oral_history' => 'Zulu Oral History',
                            'nature_ambience' => 'Nature Ambience',
                            'lullabies'       => 'Lullabies',
                            'drumming'        => 'Drumming',
                            'general'         => 'General',
                        ])
                        ->native(false)
                        ->default('general'),

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

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'live'       => 'Live',
                            'processing' => 'Processing',
                            'archived'   => 'Archived',
                        ])
                        ->native(false)
                        ->default('processing')
                        ->required()
                        ->columnSpan(2),
                ]),

            Forms\Components\Section::make('Duration & Size')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('duration_seconds')
                        ->label('Duration (seconds)')
                        ->numeric()
                        ->default(0)
                        ->suffix('sec'),

                    Forms\Components\TextInput::make('file_size_bytes')
                        ->label('File Size (bytes)')
                        ->numeric()
                        ->default(0),
                ]),

            Forms\Components\Section::make('Media')
                ->schema([
                    Forms\Components\FileUpload::make('cover_image_path')
                        ->label('Cover Art')
                        ->image()
                        ->directory('audio/covers')
                        ->maxSize(3072),

                    Forms\Components\FileUpload::make('audio_file_path')
                        ->label('Audio File (MP3 / WAV)')
                        ->directory('audio/tracks')
                        ->maxSize(102400)
                        ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4']),
                ]),

            Forms\Components\Toggle::make('is_featured')
                ->label('Featured Track')
                ->default(false),
        ]);
    }

    // ── Table ────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image_path')
                    ->label('')
                    ->height(48)
                    ->width(48),

                TextColumn::make('title')
                    ->label('Track')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (AudioTrack $r) => $r->subtitle ?? $r->category),

                TextColumn::make('tribe.name')
                    ->label('Tribe')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        'live'       => 'success',
                        'processing' => 'warning',
                        'archived'   => 'gray',
                        default      => 'gray',
                    }),

                TextColumn::make('duration_seconds')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => sprintf('%02d:%02d', intdiv($state ?? 0, 60), ($state ?? 0) % 60))
                    ->sortable(),

                TextColumn::make('play_count')
                    ->label('Plays')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'live'       => 'Live',
                        'processing' => 'Processing',
                        'archived'   => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('tribe_id')
                    ->label('Tribe')
                    ->relationship('tribe', 'name'),
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
            'index'  => Pages\ListAudioTracks::route('/'),
            'create' => Pages\CreateAudioTrack::route('/create'),
            'edit'   => Pages\EditAudioTrack::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check();
    }
}
