<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Forms\Components;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ThemeEngine extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-swatch';
    protected static UnitEnum|string|null $navigationGroup = 'SYSTEM';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Theme Engine';

    public $primaryColor = '#0f9361';
    public $secondaryColor = '#d67800';
    public $accentColor = '#9d5dff';
    public $surfaceBase = '#faf8ff';
    public $errorColor = '#c5192d';

    public function mount(): void
    {
        $this->form->fill([
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color' => $this->accentColor,
            'surface_base' => $this->surfaceBase,
            'error_color' => $this->errorColor,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Components\Section::make('Global Styles')
                ->description('Customise the brand colour palette')
                ->columns(2)
                ->schema([
                    Components\ColorPicker::make('primary_color')
                        ->label('Primary (Emerald)')
                        ->hint('Main CTA buttons, success states')
                        ->live(onBlur: true),
                    Components\ColorPicker::make('secondary_color')
                        ->label('Secondary (Amber)')
                        ->hint('Warnings, secondary highlights')
                        ->live(onBlur: true),
                    Components\ColorPicker::make('accent_color')
                        ->label('Accent (Violet)')
                        ->hint('System processes, AI insights')
                        ->live(onBlur: true),
                    Components\ColorPicker::make('error_color')
                        ->label('Error (Red)')
                        ->hint('Destructive actions, failures')
                        ->live(onBlur: true),
                ]),

            Components\Section::make('Surface & Contrast')
                ->description('Configure background and surface colors')
                ->collapsible()
                ->columns(2)
                ->schema([
                    Components\ColorPicker::make('surface_base')
                        ->label('Surface Base')
                        ->hint('Primary page background')
                        ->live(onBlur: true),
                    Components\ColorPicker::make('surface_container_low')
                        ->label('Container Low')
                        ->default('#f2f3ff')
                        ->live(onBlur: true),
                ]),

            Components\Section::make('Typography')
                ->description('Font weights and sizes')
                ->collapsible()
                ->columns(2)
                ->schema([
                    Components\Select::make('headline_font')
                        ->label('Headline Font')
                        ->options([
                            'manrope' => 'Manrope (Geometric, Bold)',
                            'inter' => 'Inter (Professional)',
                            'poppins' => 'Poppins (Friendly)',
                        ])
                        ->native(false)
                        ->default('manrope'),
                    Components\Select::make('body_font')
                        ->label('Body Font')
                        ->options([
                            'inter' => 'Inter (Legible)',
                            'source-sans' => 'Source Sans Pro',
                            'lora' => 'Lora (Serif)',
                        ])
                        ->native(false)
                        ->default('inter'),
                ]),

            Components\Section::make('Corner Radius')
                ->description('Control border radius globally')
                ->collapsible()
                ->columns(3)
                ->schema([
                    Components\TextInput::make('radius_sm')
                        ->label('Small (sm)')
                        ->numeric()
                        ->suffix('px')
                        ->default('8'),
                    Components\TextInput::make('radius_md')
                        ->label('Medium (md)')
                        ->numeric()
                        ->suffix('px')
                        ->default('12'),
                    Components\TextInput::make('radius_lg')
                        ->label('Large (lg)')
                        ->numeric()
                        ->suffix('px')
                        ->default('16'),
                    Components\TextInput::make('radius_xl')
                        ->label('Extra Large (xl)')
                        ->numeric()
                        ->suffix('px')
                        ->default('20'),
                    Components\TextInput::make('radius_2xl')
                        ->label('2XL (2xl)')
                        ->numeric()
                        ->suffix('px')
                        ->default('24'),
                ]),

            Components\Section::make('Shadow Intensity')
                ->description('Adjust shadow opacity and spread')
                ->collapsible()
                ->columns(2)
                ->schema([
                    Components\Select::make('shadow_style')
                        ->label('Shadow Style')
                        ->options([
                            'soft' => 'Soft & Ambient',
                            'bold' => 'Bold & Defined',
                            'minimal' => 'Minimal',
                        ])
                        ->native(false)
                        ->default('soft'),
                    Components\Slider::make('shadow_intensity')
                        ->label('Shadow Intensity')
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(5)
                        ->default(100)
                        ->suffix('%'),
                ]),
        ])->columns(1);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Apply Theme')
                ->submit('save')
                ->color('success')
                ->icon('heroicon-s-check'),
            Action::make('export')
                ->label('Export CSS')
                ->color('info')
                ->icon('heroicon-s-arrow-down-tray')
                ->action(fn () => $this->exportCss()),
            Action::make('reset')
                ->label('Reset to Default')
                ->color('warning')
                ->icon('heroicon-s-arrow-path')
                ->requiresConfirmation()
                ->action(fn () => $this->resetTheme()),
        ];
    }

    public function save(): void
    {
        $this->validate();

        // Save theme to database or session
        session([
            'theme' => [
                'primary_color' => $this->form->getState()['primary_color'],
                'secondary_color' => $this->form->getState()['secondary_color'],
                'accent_color' => $this->form->getState()['accent_color'],
                'error_color' => $this->form->getState()['error_color'],
            ]
        ]);

        $this->dispatch('themeUpdated');

        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Theme Updated')
            ->body('Your custom theme has been applied to the admin panel.')
            ->send();
    }

    public function exportCss()
    {
        $state = $this->form->getState();
        
        // Extract values with defaults
        $radiusSm = $state['radius_sm'] ?? 8;
        $radiusMd = $state['radius_md'] ?? 12;
        $radiusLg = $state['radius_lg'] ?? 16;
        $radiusXl = $state['radius_xl'] ?? 20;
        $radius2xl = $state['radius_2xl'] ?? 24;
        
        $css = <<<CSS
/* Paulette Culture Kids - Custom Theme */
/* Generated: {$this->getGeneratedTime()} */

:root {
    --color-primary: {$state['primary_color']};
    --color-secondary: {$state['secondary_color']};
    --color-accent: {$state['accent_color']};
    --color-error: {$state['error_color']};
    --color-surface-base: {$state['surface_base']};
    
    --radius-sm: {$radiusSm}px;
    --radius-md: {$radiusMd}px;
    --radius-lg: {$radiusLg}px;
    --radius-xl: {$radiusXl}px;
    --radius-2xl: {$radius2xl}px;
}

/* Apply primary gradient to CTAs */
.fi-btn-primary, button.fi-btn-primary {{
    background: linear-gradient(135deg, var(--color-primary) 0%, lighten(var(--color-primary), 20%) 100%);
}}

/* Surface backgrounds */
body {{
    background-color: var(--color-surface-base);
}}

/* Cards with custom radius */
.fi-card, [class*='card'] {{
    border-radius: var(--radius-2xl);
}}
CSS;

        return response()
            ->streamDownload(
                fn () => print($css),
                'paulette-theme-' . now()->format('Y-m-d-His') . '.css'
            );
    }

    public function resetTheme(): void
    {
        $this->primaryColor = '#0f9361';
        $this->secondaryColor = '#d67800';
        $this->accentColor = '#9d5dff';
        $this->surfaceBase = '#faf8ff';
        $this->errorColor = '#c5192d';

        $this->form->fill([
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color' => $this->accentColor,
            'surface_base' => $this->surfaceBase,
            'error_color' => $this->errorColor,
        ]);

        \Filament\Notifications\Notification::make()
            ->info()
            ->title('Theme Reset')
            ->body('Theme has been reset to default values.')
            ->send();
    }

    private function getGeneratedTime(): string
    {
        return now()->format('Y-m-d H:i:s');
    }

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }
}
