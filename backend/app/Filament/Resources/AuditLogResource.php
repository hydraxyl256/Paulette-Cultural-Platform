<?php

namespace App\Filament\Resources;

use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Infolist\Components\Placeholder;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\AuditLogResource\Pages;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    // Avoid colliding with legacy Laravel route:
    // routes/web.php defines GET /admin/audit-logs, which prevents Filament
    // from registering the resource index route at the same URI.
    protected static ?string $slug = 'audit-logs-management';

    // Filament 3 type for navigation icon
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 4;
    protected static UnitEnum|string|null $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel = 'Audit Logs';
    protected static ?string $recordTitleAttribute = 'action';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Audit Event Details')
                    ->schema([
                        Placeholder::make('user')
                            ->label('User')
                            ->content(fn (AuditLog $record) => $record->user->name . ' (' . $record->user->email . ')'),

                        Placeholder::make('action')
                            ->label('Action')
                            ->content(fn (AuditLog $record) => ucfirst(str_replace('_', ' ', $record->action))),

                        Placeholder::make('created_at')
                            ->label('Timestamp')
                            ->content(fn (AuditLog $record) => $record->created_at->format('M d, Y H:i:s')),

                        Placeholder::make('ip_address')
                            ->label('IP Address')
                            ->content(fn (AuditLog $record) => $record->ip_address ?? 'Unknown'),
                    ]),

                Forms\Components\Section::make('Affected Resource')
                    ->visible(fn (AuditLog $record) => $record->model_type !== null)
                    ->schema([
                        Placeholder::make('model_type')
                            ->label('Resource Type')
                            ->content(fn (AuditLog $record) => $record->model_type),

                        Placeholder::make('model_id')
                            ->label('Resource ID')
                            ->content(fn (AuditLog $record) => $record->model_id),
                    ]),

                Forms\Components\Section::make('Old Values')
                    ->visible(fn (AuditLog $record) => $record->old_values !== null && count($record->old_values) > 0)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Placeholder::make('old_values')
                            ->labelHidden()
                            ->content(fn (AuditLog $record) =>
                                '<code style="word-break: break-all; display: block; padding: 12px; background: #f3f4f6; border-radius: 8px;">' .
                                json_encode($record->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) .
                                '</code>'
                            )
                            ->html(),
                    ]),

                Forms\Components\Section::make('New Values')
                    ->visible(fn (AuditLog $record) => $record->new_values !== null && count($record->new_values) > 0)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Placeholder::make('new_values')
                            ->labelHidden()
                            ->content(fn (AuditLog $record) =>
                                '<code style="word-break: break-all; display: block; padding: 12px; background: #f3f4f6; border-radius: 8px;">' .
                                json_encode($record->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) .
                                '</code>'
                            )
                            ->html(),
                    ]),

                Forms\Components\Section::make('Impersonation')
                    ->visible(fn (AuditLog $record) => $record->impersonator_id !== null)
                    ->schema([
                        Placeholder::make('impersonator')
                            ->label('Impersonated By')
                            ->content(fn (AuditLog $record) =>
                                $record->impersonator->name . ' (' . $record->impersonator->email . ')'
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('M d H:i:s')
                    ->sortable()
                    ->icon('heroicon-s-clock'),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(['users.name', 'users.email'])
                    ->sortable(),

                BadgeColumn::make('action')
                    ->label('Action')
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state)))
                    ->colors([
                        'success' => 'created',
                        'info' => 'updated',
                        'warning' => 'deleted',
                        'secondary' => 'viewed',
                        'danger' => 'impersonated_by',
                    ])
                    ->icons([
                        'heroicon-s-plus-circle' => 'created',
                        'heroicon-s-pencil-square' => 'updated',
                        'heroicon-s-trash' => 'deleted',
                        'heroicon-s-eye' => 'viewed',
                        'heroicon-s-arrow-right-end-on-rectangle' => 'impersonated_by',
                    ]),

                TextColumn::make('model_type')
                    ->label('Resource')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? str($state)->afterLast('\\')->toString() : '—'),

                TextColumn::make('model_id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->sortable()
                    ->copyable()
                    ->hidden(),

                TextColumn::make('impersonator.name')
                    ->label('Impersonator')
                    ->visible(fn ($record) => $record?->impersonator_id !== null)
                    ->badge()
                    ->color('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('Action Type')
                    ->multiple()
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'viewed' => 'Viewed',
                        'impersonated_by' => 'Impersonated',
                    ]),

                Tables\Filters\SelectFilter::make('model_type')
                    ->label('Resource Type')
                    ->multiple()
                    ->options([
                        'App\Models\User' => 'User',
                        'App\Models\Organisation' => 'Organisation',
                        'App\Models\Comic' => 'Comic',
                        'App\Models\Tribe' => 'Tribe',
                        'App\Models\AgeProfile' => 'Age Profile',
                        'App\Models\ChildProfile' => 'Child Profile',
                    ]),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('From Date'),
                        DatePicker::make('created_until')->label('To Date'),
                    ])
                    ->query(fn ($query, array $data) =>
                        $query
                            ->when($data['created_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    ),
            ])
            ->actions([
                // Row interactions handled by custom Livewire slide-over panel in the blade view
            ])
            ->bulkActions([
                // Audit logs are read-only
            ]);
    }

    public static function canViewAny(): bool
    {
        return Auth::check(); // accessible to any authenticated admin user
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}