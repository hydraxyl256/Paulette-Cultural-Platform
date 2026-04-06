<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 3;
    protected static UnitEnum|string|null $navigationGroup = 'PLATFORM';
    protected static ?string $navigationLabel = 'All Users';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Tabs::make('Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Account')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Forms\Components\Section::make('User Information')
                                ->description('Basic account details')
                                ->columns(2)
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Full Name')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('email')
                                        ->label('Email Address')
                                        ->email()
                                        ->required()
                                        ->unique(ignorable: fn ($record) => $record)
                                        ->maxLength(255),
                                    Forms\Components\Select::make('org_id')
                                        ->label('Organisation')
                                        ->relationship('organisation', 'name')
                                        ->required()
                                        ->native(false)
                                        ->searchable(),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Security')
                        ->icon('heroicon-o-lock-closed')
                        ->schema([
                            Forms\Components\Section::make('Authentication')
                                ->description('Password and access management')
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\TextInput::make('password')
                                        ->label('Password')
                                        ->password()
                                        ->revealable()
                                        ->minLength(8)
                                        ->required(fn ($livewire) => $livewire->operation === 'create')
                                        ->dehydrated(fn ($state) => filled($state))
                                        ->placeholder('Leave blank to keep current password'),
                                    
                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Account Active')
                                        ->inline()
                                        ->default(true)
                                        ->helperText('Disable to prevent login'),
                                    
                                    Forms\Components\Placeholder::make('email_verified_at')
                                        ->label('Email Verified')
                                        ->content(fn (User $record) => $record?->email_verified_at ? '✓ ' . $record->email_verified_at->format('M d, Y H:i') : '✗ Not verified'),
                                ]),
                        ]),
                    
                    Forms\Components\Tabs\Tab::make('Role')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Forms\Components\Section::make('Permissions')
                                ->description('User role and access level')
                                ->collapsible(false)
                                ->schema([
                                    Forms\Components\Select::make('role')
                                        ->label('Role')
                                        ->options([
                                            'super_admin' => '👑 Super Admin (Full Access)',
                                            'org_admin' => '🏢 Organisation Admin',
                                            'teacher' => '👨‍🏫 Teacher/Curator',
                                            'parent' => '👨‍👩‍👧 Parent/Guardian',
                                        ])
                                        ->required()
                                        ->native(false),
                                    
                                    Forms\Components\Placeholder::make('role_info')
                                        ->label('Role Information')
                                        ->content(fn (Forms\Get $get) => match($get('role')) {
                                            'super_admin' => 'Full system access, user management, all features',
                                            'org_admin' => 'Can manage organisation settings and users',
                                            'teacher' => 'Can create and manage content for assigned organisation',
                                            'parent' => 'Can view and manage child profiles and progress',
                                            default => 'Select a role to see details'
                                        }),
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
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('organisation.name')
                    ->label('Organisation')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'super_admin' => 'Super Admin',
                        'org_admin'   => 'Org Admin',
                        'cms_editor'  => 'Content Editor',
                        'teacher'     => 'Teacher',
                        'parent'      => 'Parent',
                        default       => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'super_admin' => 'warning',
                        'org_admin'   => 'primary',
                        'cms_editor'  => 'success',
                        'teacher'     => 'gray',
                        default       => 'gray',
                    }),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->multiple()
                    ->options([
                        'super_admin' => 'Super Admin',
                        'org_admin' => 'Org Admin',
                        'teacher' => 'Teacher',
                        'parent' => 'Parent',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verified'),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-s-pencil-square'),
                Action::make('impersonate')
                    ->label('Impersonate')
                    ->icon('heroicon-s-arrow-right-end-on-rectangle')
                    ->color('info')
                    ->visible(fn (User $record) => $record->id !== Auth::id())
                    ->requiresConfirmation()
                    ->modalHeading('Impersonate User')
                    ->modalDescription('You will assume this user\'s identity. All actions will be logged.')
                    ->modalSubmitActionLabel('Yes, Impersonate')
                    ->action(function (User $record) {
                        AuditLog::create([
                            'user_id'     => $record->id,
                            'action'      => 'impersonated_by_admin',
                            'description' => 'Admin ' . Auth::user()->name . ' impersonated this user',
                            'metadata'    => [
                                'impersonator_id'   => Auth::id(),
                                'impersonator_name' => Auth::user()->name,
                            ],
                        ]);
                        session([
                            'impersonating_user_id'    => $record->id,
                            'impersonator_id'          => Auth::id(),
                            'impersonation_started_at' => now(),
                        ]);
                        Notification::make()->success()->title('Impersonation Started')
                            ->body("You are now viewing as {$record->name} ({$record->email})")->send();
                        return redirect('/admin');
                    }),
                DeleteAction::make()
                    ->icon('heroicon-s-trash'),
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
        return Auth::user()->hasRole('super_admin');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->hasRole('super_admin') && $record->id !== Auth::id();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
