<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\Widget;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use BackedEnum;
use UnitEnum;

class ErrorLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = true;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static UnitEnum|string|null $navigationGroup = 'SYSTEM';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Error Logs';

    public function getTable(): Table
    {
        $logs = $this->parseErrorLogs();

        return $this->table(
            Tables\Table::make()
                ->columns([
                    TextColumn::make('timestamp')
                        ->label('Time')
                        ->dateTime('M d, H:i:s')
                        ->sortable()
                        ->icon('heroicon-s-clock'),

                    BadgeColumn::make('level')
                        ->label('Level')
                        ->formatStateUsing(fn ($state) => strtoupper($state))
                        ->colors([
                            'danger' => 'error',
                            'warning' => 'warning',
                            'info' => 'info',
                            'secondary' => 'debug',
                        ])
                        ->icons([
                            'heroicon-s-x-circle' => 'error',
                            'heroicon-s-exclamation-circle' => 'warning',
                            'heroicon-s-information-circle' => 'info',
                            'heroicon-s-bug-ant' => 'debug',
                        ]),

                    TextColumn::make('message')
                        ->label('Message')
                        ->searchable()
                        ->wrap()
                        ->limit(100),

                    TextColumn::make('file')
                        ->label('File')
                        ->searchable()
                        ->hidden(),

                    TextColumn::make('line')
                        ->label('Line')
                        ->sortable()
                        ->hidden(),
                ])
                ->filters([
                    Tables\Filters\SelectFilter::make('level')
                        ->label('Log Level')
                        ->multiple()
                        ->options([
                            'error' => 'Error',
                            'warning' => 'Warning',
                            'info' => 'Info',
                            'debug' => 'Debug',
                        ]),
                ])
                ->actions([
                    Tables\Actions\Action::make('details')
                        ->label('Details')
                        ->icon('heroicon-s-eye')
                        ->modalHeading('Error Details')
                        ->modalContent(fn ($record) => view('filament.components.error-details', ['record' => $record])),
                    Tables\Actions\Action::make('delete')
                        ->icon('heroicon-s-trash')
                        ->color('danger')
                        ->requiresConfirmation(),
                ])
                ->paginated([10, 25, 50])
                ->defaultPaginationPageOption(10)
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ])
        );
    }

    private function parseErrorLogs(): array
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return [];
        }

        $logs = [];
        $content = File::get($logFile);
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            if (preg_match('/(ERROR|WARNING|INFO|DEBUG)/', $line)) {
                $logs[] = [
                    'timestamp' => now(),
                    'level' => 'error',
                    'message' => $line,
                    'file' => 'laravel.log',
                    'line' => 0,
                ];
            }
        }

        return array_reverse($logs);
    }

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }
}
