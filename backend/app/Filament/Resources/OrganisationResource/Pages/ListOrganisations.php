<?php

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use App\Models\Organisation;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListOrganisations extends ListRecords
{
    protected static string $resource = OrganisationResource::class;

    public function getView(): string
    {
        return 'filament.pages.organisations-list';
    }

    // ── Filter state ─────────────────────────────────────────────
    public string $ckPlanFilter = '';
    public string $ckStatusFilter = '';
    public string $ckRegionFilter = '';
    public string $ckTab = 'active';
    public string $ckSearch = '';

    public function setCkTab(string $tab): void
    {
        $this->ckTab = $tab;
    }

    public function ckResetFilters(): void
    {
        $this->ckPlanFilter = '';
        $this->ckStatusFilter = '';
        $this->ckRegionFilter = '';
        $this->ckSearch = '';
    }

    public function suspendOrg(int $id): void
    {
        Organisation::where('id', $id)->update(['is_active' => false]);
    }

    public function activateOrg(int $id): void
    {
        Organisation::where('id', $id)->update(['is_active' => true]);
    }

    // ── Data provider ────────────────────────────────────────────

    protected function getViewData(): array
    {
        $query = Organisation::query()
            ->withCount(['users', 'childProfiles']);

        // Tab filter
        if ($this->ckTab === 'active') {
            $query->where('is_active', true);
        } else {
            $query->where('is_active', false);
        }

        // Plan filter
        if ($this->ckPlanFilter) {
            $query->where('plan', $this->ckPlanFilter);
        }

        // Search
        if ($this->ckSearch) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->ckSearch}%")
                  ->orWhere('slug', 'like', "%{$this->ckSearch}%");
            });
        }

        $organisations = $query->orderBy('name')->get();
        $totalOrganisations = Organisation::count();

        return [
            'organisations' => $organisations,
            'totalOrganisations' => $totalOrganisations,
            'createUrl' => OrganisationResource::getUrl('create'),
            'ckTab' => $this->ckTab,
        ];
    }
}
