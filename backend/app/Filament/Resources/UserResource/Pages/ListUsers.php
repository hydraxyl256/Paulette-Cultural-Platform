<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Models\Organisation;
use App\Models\AuditLog;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getView(): string
    {
        return 'filament.pages.users-list';
    }

    // ── Filter / search state ─────────────────────────────────────
    public string $ckSearch       = '';
    public string $ckRoleFilter   = '';
    public string $ckStatusFilter = '';
    public string $ckOrgFilter    = '';

    // ── Bulk selection state ──────────────────────────────────────
    public array $ckSelected = [];
    public bool  $ckAllSelected = false;

    // ── Advanced filter panel toggle ──────────────────────────────
    public bool $ckShowFilters = false;

    // ── Updating hooks ────────────────────────────────────────────
    public function updatingCkSearch(): void   { $this->ckSelected = []; }
    public function updatingCkRoleFilter(): void  { $this->ckSelected = []; }
    public function updatingCkStatusFilter(): void { $this->ckSelected = []; }

    // ── Actions ───────────────────────────────────────────────────
    public function ckResetFilters(): void
    {
        $this->ckSearch = '';
        $this->ckRoleFilter = '';
        $this->ckStatusFilter = '';
        $this->ckOrgFilter = '';
        $this->ckSelected = [];
    }

    public function ckToggleUser(int $id): void
    {
        if (in_array($id, $this->ckSelected)) {
            $this->ckSelected = array_values(array_filter($this->ckSelected, fn($v) => $v !== $id));
        } else {
            $this->ckSelected[] = $id;
        }
    }

    public function ckSelectAll(): void
    {
        $ids = $this->ckQuery()->pluck('id')->map(fn($id) => (int)$id)->toArray();
        if (count($this->ckSelected) === count($ids)) {
            $this->ckSelected = [];
            $this->ckAllSelected = false;
        } else {
            $this->ckSelected = $ids;
            $this->ckAllSelected = true;
        }
    }

    public function ckSuspendSelected(): void
    {
        User::whereIn('id', $this->ckSelected)->update(['is_active' => false]);
        $this->ckSelected = [];
    }

    public function ckDeleteSelected(): void
    {
        User::whereIn('id', $this->ckSelected)
            ->where('id', '!=', Auth::id())
            ->delete();
        $this->ckSelected = [];
    }

    public function ckChangeRole(string $role): void
    {
        User::whereIn('id', $this->ckSelected)->update(['role' => $role]);
        $this->ckSelected = [];
    }

    public function ckSuspendUser(int $id): void
    {
        User::where('id', $id)->update(['is_active' => false]);
    }

    public function ckActivateUser(int $id): void
    {
        User::where('id', $id)->update(['is_active' => true]);
    }

    // ── Base query ────────────────────────────────────────────────
    protected function ckQuery(): Builder
    {
        $query = User::query()->with('organisation');

        if ($this->ckSearch) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->ckSearch}%")
                  ->orWhere('email', 'like', "%{$this->ckSearch}%");
            });
        }
        if ($this->ckRoleFilter) {
            $query->where('role', $this->ckRoleFilter);
        }
        if ($this->ckStatusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->ckStatusFilter === 'inactive') {
            $query->where('is_active', false);
        }
        if ($this->ckOrgFilter) {
            $query->where('org_id', $this->ckOrgFilter);
        }

        return $query->orderBy('name');
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        $users      = $this->ckQuery()->get();
        $totalUsers = User::count();
        $organisations = Organisation::orderBy('name')->get(['id', 'name']);

        // Build last-activity lookup from audit_logs
        $lastActivity = AuditLog::selectRaw('user_id, MAX(created_at) as last_at')
            ->groupBy('user_id')
            ->pluck('last_at', 'user_id');

        return [
            'users'         => $users,
            'totalUsers'    => $totalUsers,
            'organisations' => $organisations,
            'lastActivity'  => $lastActivity,
            'createUrl'     => UserResource::getUrl('create'),
        ];
    }
}
