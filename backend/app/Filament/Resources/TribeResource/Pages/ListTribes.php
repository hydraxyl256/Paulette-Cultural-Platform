<?php

namespace App\Filament\Resources\TribeResource\Pages;

use App\Filament\Resources\TribeResource;
use App\Models\Tribe;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTribes extends ListRecords
{
    protected static string $resource = TribeResource::class;

    public function getView(): string
    {
        return 'filament.pages.tribes-list';
    }

    // ── Search / filter state ────────────────────────────────────
    public string  $ckSearch       = '';
    public string  $ckRegionFilter = '';
    public string  $ckStatusFilter = '';

    // ── Featured tribe (hero card) ───────────────────────────────
    public ?int $ckFeaturedId = null;

    // ── Expanded preview sections ────────────────────────────────
    public array $ckExpanded = [];

    // ── Update hooks ─────────────────────────────────────────────
    public function updatingCkSearch(): void { }

    // ── Actions ──────────────────────────────────────────────────
    public function ckResetFilters(): void
    {
        $this->ckSearch = '';
        $this->ckRegionFilter = '';
        $this->ckStatusFilter = '';
    }

    public function ckToggleExpanded(int $id): void
    {
        if (in_array($id, $this->ckExpanded)) {
            $this->ckExpanded = array_values(array_filter($this->ckExpanded, fn($v) => $v !== $id));
        } else {
            $this->ckExpanded[] = $id;
        }
    }

    public function ckSetFeatured(int $id): void
    {
        $this->ckFeaturedId = $id;
    }

    public function ckToggleActive(int $id): void
    {
        $tribe = Tribe::find($id);
        if ($tribe) {
            $tribe->update(['is_active' => !$tribe->is_active]);
        }
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        $query = Tribe::withCount('comics');

        if ($this->ckSearch) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->ckSearch}%")
                  ->orWhere('language', 'like', "%{$this->ckSearch}%")
                  ->orWhere('region', 'like', "%{$this->ckSearch}%");
            });
        }
        if ($this->ckRegionFilter) {
            $query->where('region', $this->ckRegionFilter);
        }
        if ($this->ckStatusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->ckStatusFilter === 'archived') {
            $query->where('is_active', false);
        }

        $tribes = $query->orderBy('name')->get();

        // Featured tribe: first active, or just first
        $featuredId = $this->ckFeaturedId;
        $featured = $featuredId
            ? $tribes->firstWhere('id', $featuredId) ?? $tribes->first()
            : ($tribes->firstWhere('is_active', true) ?? $tribes->first());

        // Distribution stats
        $activeCnt   = Tribe::where('is_active', true)->count();
        $archivedCnt = Tribe::where('is_active', false)->count();
        $total       = max($activeCnt + $archivedCnt, 1);

        // All regions for filter
        $regions = Tribe::distinct()->orderBy('region')->pluck('region')->filter()->values();

        // Grid tribes = all except featured
        $gridTribes = $featured ? $tribes->where('id', '!=', $featured->id) : $tribes;

        return [
            'tribes'       => $tribes,
            'gridTribes'   => $gridTribes,
            'featured'     => $featured,
            'activeCnt'    => $activeCnt,
            'archivedCnt'  => $archivedCnt,
            'total'        => $total,
            'regions'      => $regions,
            'createUrl'    => TribeResource::getUrl('create'),
        ];
    }
}
