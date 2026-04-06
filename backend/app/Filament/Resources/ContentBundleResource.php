<?php

namespace App\Filament\Resources;

use App\Models\ContentBundle;
use Filament\Forms;
use Filament\Forms\Components\Section;
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
use App\Filament\Resources\ContentBundleResource\Pages;

class ContentBundleResource extends Resource
{
    protected static ?string $model = ContentBundle::class;
    protected static string|\BackedEnum|null  $navigationIcon  = 'heroicon-o-cube';
    protected static ?int    $navigationSort  = 5;
    protected static string|\UnitEnum|null $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel = 'Bundle Builder';
    protected static ?string $recordTitleAttribute = 'title';
    protected static bool    $shouldRegisterNavigation = false; // Nav handled by BundleBuilderPage

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Bundle Metadata')->columns(2)->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Bundle Title')->required()->maxLength(255)->columnSpan(2),
                Forms\Components\Select::make('tribe_id')
                    ->label('Target Tribe')->relationship('tribe', 'name')
                    ->native(false)->searchable(),
                Forms\Components\TextInput::make('age_range')
                    ->label('Age Range')->default('3 - 6 Years'),
                Forms\Components\TextInput::make('deployment_version')
                    ->label('Deployment Version')->default('v2.4.1')->columnSpan(2),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(['draft'=>'Draft','building'=>'Building','shipped'=>'Shipped','failed'=>'Failed'])
                    ->native(false)->default('draft')->columnSpan(2),
                Forms\Components\Toggle::make('encryption_enabled')
                    ->label('Military Grade Encryption (AES-256)')->default(true)->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Bundle')->searchable()->sortable()->weight('bold')
                    ->description(fn (ContentBundle $r) => $r->tribe?->name ? 'Tribe: ' . $r->tribe->name : null),
                TextColumn::make('tribe.name')->label('Tribe')->sortable()->badge()->color('primary'),
                TextColumn::make('age_range')->label('Age Range'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($s) => match($s) {
                        'shipped'  => 'success',
                        'building' => 'warning',
                        'failed'   => 'danger',
                        default    => 'gray',
                    }),
                TextColumn::make('bundle_size_bytes')->label('Size')
                    ->formatStateUsing(fn (ContentBundle $r) => $r->formattedSize()),
                TextColumn::make('build_readiness_pct')->label('Readiness')
                    ->formatStateUsing(fn ($s) => $s . '%'),
                TextColumn::make('updated_at')->label('Last Updated')->dateTime('M d, Y')->sortable(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContentBundles::route('/'),
            'create' => Pages\CreateContentBundle::route('/create'),
            'edit'   => Pages\EditContentBundle::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return Auth::check(); }
}
