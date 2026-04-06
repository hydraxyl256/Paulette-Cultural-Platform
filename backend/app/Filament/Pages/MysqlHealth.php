<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use UnitEnum;

class MysqlHealth extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-circle-stack';

    protected static UnitEnum|string|null $navigationGroup = 'SYSTEM';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'MySQL Health';

    protected static ?string $navigationLabel = 'MySQL Health';

    public function content(Schema $schema): Schema
    {
        $status = 'Connected';
        $detail = '';

        try {
            DB::selectOne('select 1 as ok');
            $driver = config('database.default');
            $connection = config("database.connections.{$driver}");
            $database = $connection['database'] ?? '—';
            $detail = "Driver: {$driver} · Database: {$database}";
        } catch (Throwable $e) {
            $status = 'Unavailable';
            $detail = $e->getMessage();
        }

        return $schema
            ->components([
                Section::make('Database status')
                    ->description('Quick connectivity check for the primary Laravel database connection.')
                    ->components([
                        Text::make($status)->weight('bold'),
                        Text::make($detail)->color('gray'),
                    ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }
}
