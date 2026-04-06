<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class KpiMetricCard extends Widget
{
    protected string $view = 'filament.widgets.kpi-metric-card';

    public string $title = '';
    public string $value = '';
    public string $unit = '';
    public string $trend = '';
    public string $trendDirection = 'up'; // up, down, stable
    public string $color = 'emerald'; // emerald, amber, violet
    public ?string $icon = null;
    public string $size = 'md'; // sm, md, lg
    public bool $showTrend = true;

    public function getData(): array
    {
        return [
            'title' => $this->title,
            'value' => $this->value,
            'unit' => $this->unit,
            'trend' => $this->trend,
            'trendDirection' => $this->trendDirection,
            'color' => $this->color,
            'icon' => $this->icon,
            'size' => $this->size,
            'showTrend' => $this->showTrend,
        ];
    }
}
