<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ThemeEngine extends Page
{
    // ── Page settings ────────────────────────────────────────────
    protected string $view = 'filament.pages.theme-engine';

    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-swatch';
    protected static UnitEnum|string|null   $navigationGroup = 'SYSTEM';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $title           = 'Theme Engine';
    protected static ?string $navigationLabel = 'Theme Engine';

    // ── Livewire state ────────────────────────────────────────────
    public int $priHue = 158;
    public int $priSat = 100;
    
    public int $secHue = 33;
    public int $secLig = 28;
    
    public int $accHue = 265;
    public int $accVib = 72;
    
    public int $cornerRadius = 40;
    public string $contrastMode = 'Sophisticated';

    // ── Actions ────────────────────────────────────────────────────
    public function ckSetContrast(string $mode): void
    {
        $this->contrastMode = $mode;
    }

    public function ckSyncActive(): void
    {
        Notification::make()->success()
            ->title('Theme Synced')
            ->body('Live identity changes synchronized across all nodes.')
            ->send();
    }
    
    // Convert HSL to Hex for display
    protected function hslToHex($h, $s, $l) {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        
        $r = $l;
        $g = $l;
        $b = $l;
        
        $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
        if ($v > 0) {
            $m = $l + $l - $v;
            $sv = ($v - $m) / $v;
            $h *= 6.0;
            $sextant = floor($h);
            $fract = $h - $sextant;
            $vsf = $v * $sv * $fract;
            $mid1 = $m + $vsf;
            $mid2 = $v - $vsf;
            switch ($sextant) {
                case 0: $r = $v; $g = $mid1; $b = $m; break;
                case 1: $r = $mid2; $g = $v; $b = $m; break;
                case 2: $r = $m; $g = $v; $b = $mid1; break;
                case 3: $r = $m; $g = $mid2; $b = $v; break;
                case 4: $r = $mid1; $g = $m; $b = $v; break;
                case 5: $r = $v; $g = $m; $b = $mid2; break;
            }
        }
        $r = round($r * 255);
        $g = round($g * 255);
        $b = round($b * 255);
        
        return sprintf("#%02X%02X%02X", $r, $g, $b);
    }

    protected function getViewData(): array
    {
        // Calculate hex colors based on sliders for display purposes
        // HSL -> Hex logic loosely approximated here or just passed as HSL
        
        // For accurate display we will use CSS HSL() heavily, but calculate Hex for the badges
        $priHex = $this->hslToHex($this->priHue, $this->priSat, 35); // Approx 35% lightness for dark green
        $secHex = $this->hslToHex($this->secHue, 100, $this->secLig);
        $accHex = $this->hslToHex($this->accHue, $this->accVib, 60);

        return [
            'priHex' => $priHex,
            'secHex' => $secHex,
            'accHex' => $accHex,
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::check();
    }
}
