<?php

namespace App\Filament\Resources;

use Spatie\Permission\Models\Role;
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
use App\Filament\Resources\RbacRoleResource\Pages;

class RbacRoleResource extends Resource
{
    protected static ?string $model               = Role::class;
    protected static ?string $slug                = 'rbac-role-forms'; // avoids URL conflict with /admin/rbac-roles (owned by RbacRoles Page)
    protected static string|\BackedEnum|null $navigationIcon  = 'heroicon-o-shield-check';
    protected static ?int    $navigationSort      = 1;
    protected static string|\UnitEnum|null $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel     = 'RBAC Roles';
    protected static ?string $recordTitleAttribute = 'name';
    protected static bool    $shouldRegisterNavigation = false; // Nav handled by RbacRoles Page

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Role Information')->columns(2)->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpan(2),

                Forms\Components\TextInput::make('guard_name')
                    ->label('Guard')
                    ->default('web')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('permissions')
                    ->label('Permissions')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('Permissions')
                    ->sortable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRbacRoles::route('/'),
            'create' => Pages\CreateRbacRole::route('/create'),
            'edit'   => Pages\EditRbacRole::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return Auth::check(); }
}
