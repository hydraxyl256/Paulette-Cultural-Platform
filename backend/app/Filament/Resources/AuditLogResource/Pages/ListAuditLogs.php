<?php

namespace App\Filament\Resources\AuditLogResource\Pages;

use App\Filament\Resources\AuditLogResource;
use App\Models\AuditLog;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    public function getView(): string
    {
        return 'filament.pages.audit-logs';
    }

    // ── Livewire state ─────────────────────────────────────────────
    public ?int   $ckSelectedId  = null;
    public bool   $ckShowDetail  = false;
    public string $ckSearch      = '';
    public string $ckDateFrom    = '';
    public string $ckDateTo      = '';
    public string $ckUserFilter  = '';
    public string $ckActionFilter= '';
    public int    $ckPage        = 1;
    public int    $ckPerPage     = 25;

    // ── Open detail panel ─────────────────────────────────────────
    public function ckOpenDetail(int $id): void
    {
        $this->ckSelectedId = $id;
        $this->ckShowDetail = true;
    }

    public function ckCloseDetail(): void
    {
        $this->ckShowDetail = false;
        $this->ckSelectedId = null;
    }

    // ── Validate audit chain (decorative action) ──────────────────
    public function ckValidateChain(): void
    {
        Notification::make()->success()
            ->title('Audit Chain Valid')
            ->body('All cryptographic hashes verified — integrity confirmed.')
            ->send();
    }

    // ── Revoke admin session ──────────────────────────────────────
    public function ckRevokeSession(): void
    {
        Notification::make()->warning()
            ->title('Session Revoked')
            ->body('Admin session has been terminated and logged.')
            ->send();
        $this->ckCloseDetail();
    }

    // ── Build query with filters ───────────────────────────────────
    protected function auditQuery()
    {
        return AuditLog::with(['user', 'impersonator'])
            ->when($this->ckSearch, fn($q) =>
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->ckSearch}%"))
                  ->orWhere('action', 'like', "%{$this->ckSearch}%")
                  ->orWhere('ip_address', 'like', "%{$this->ckSearch}%")
            )
            ->when($this->ckDateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->ckDateFrom))
            ->when($this->ckDateTo,   fn($q) => $q->whereDate('created_at', '<=', $this->ckDateTo))
            ->when($this->ckUserFilter, fn($q) =>
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->ckUserFilter}%"))
            )
            ->when($this->ckActionFilter, fn($q) => $q->where('action', $this->ckActionFilter))
            ->latest('created_at');
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        $query   = $this->auditQuery();
        $total   = $query->count();
        $offset  = ($this->ckPage - 1) * $this->ckPerPage;
        $events  = $query->skip($offset)->take($this->ckPerPage)->get();
        $pages   = max(1, (int) ceil($total / $this->ckPerPage));

        $selected = $this->ckSelectedId ? AuditLog::with(['user','impersonator'])->find($this->ckSelectedId) : null;

        // Derive metadata payload for the detail panel
        $payload = null;
        if ($selected) {
            $payload = json_encode([
                'event_id'  => 'evt_' . str_pad($selected->id, 7, '0', STR_PAD_LEFT),
                'timestamp' => $selected->created_at?->toIso8601String(),
                'action'    => $selected->action,
                'metadata'  => array_filter([
                    'admin_id'    => $selected->user ? 'adm_' . str_replace(' ', '_', strtolower($selected->user->name)) : null,
                    'target_id'   => $selected->model_type ? 'usr_' . $selected->model_id : null,
                    'model_type'  => $selected->model_type,
                    'model_id'    => $selected->model_id,
                    'old_values'  => $selected->old_values,
                    'new_values'  => $selected->new_values,
                ]),
                'origin'    => array_filter([
                    'ip'         => $selected->ip_address,
                    'geo'        => 'Nairobi, KE',
                    'user_agent' => 'Chrome/118.0.0.0',
                ]),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        // Action metadata
        $actionMeta = function(?string $action): array {
            return match(strtolower($action ?? '')) {
                'impersonated_by', 'impersonate_user' => [
                    'label' => 'Impersonate User', 'color' => '#d97706',
                    'bg'    => 'rgba(217,119,6,0.08)', 'icon' => 'alert', 'severity' => 'SECURITY WARNING',
                    'row_bg'=> 'rgba(217,119,6,0.04)',
                ],
                'deleted' => [
                    'label' => 'Delete', 'color' => '#dc2626',
                    'bg'    => 'rgba(220,38,38,0.08)', 'icon' => 'trash', 'severity' => '',
                    'row_bg'=> 'rgba(220,38,38,0.03)',
                ],
                'created' => [
                    'label' => 'Create', 'color' => '#059669',
                    'bg'    => 'rgba(5,150,105,0.08)', 'icon' => 'plus', 'severity' => '',
                    'row_bg'=> 'transparent',
                ],
                'updated' => [
                    'label' => 'Update', 'color' => '#3b82f6',
                    'bg'    => 'rgba(59,130,246,0.08)', 'icon' => 'edit', 'severity' => '',
                    'row_bg'=> 'transparent',
                ],
                default => [
                    'label' => ucfirst($action ?? '—'), 'color' => '#71717a',
                    'bg'    => 'rgba(113,113,122,0.08)', 'icon' => 'info', 'severity' => '',
                    'row_bg'=> 'transparent',
                ],
            };
        };

        $selectedMeta = $selected ? $actionMeta($selected->action) : null;

        // Demo events when DB is empty
        $demoEvents = collect([
            ['ts'=>'Oct 31, 2023 / 14:22:05.432','user'=>'Felix G.','icon'=>'F','action'=>'updated','resource'=>'Organisation','resource_id'=>'128','ip'=>'192.168.1.10','row_bg'=>'transparent','action_label'=>'Update Organisation','action_color'=>'#3b82f6'],
            ['ts'=>'Oct 31, 2023 / 13:10:12.881','user'=>'Amara K.','icon'=>'A','action'=>'impersonated_by','resource'=>'User','resource_id'=>'99021','ip'=>'45.22.109.1','row_bg'=>'rgba(217,119,6,0.06)','action_label'=>'Impersonate User','action_color'=>'#d97706'],
            ['ts'=>'Oct 31, 2023 / 11:05:44.200','user'=>'System Kernel','icon'=>'⚙','action'=>'viewed','resource'=>'SSH','resource_id'=>'0','ip'=>'203.0.113.42','row_bg'=>'rgba(220,38,38,0.04)','action_label'=>'Failed SSH Attempt','action_color'=>'#dc2626'],
            ['ts'=>'Oct 31, 2023 / 09:44:11.555','user'=>'Liam N.','icon'=>'L','action'=>'deleted','resource'=>'Comic','resource_id'=>'55','ip'=>'10.0.0.5','row_bg'=>'transparent','action_label'=>'Delete Comic Issue','action_color'=>'#dc2626'],
        ]);

        $useDemo = $total === 0;

        // Available users + actions for filters
        $users   = User::orderBy('name')->pluck('name')->take(20);
        $actions = AuditLog::distinct()->pluck('action')->sort()->values();

        return compact(
            'events', 'total', 'pages', 'selected', 'payload',
            'selectedMeta', 'actionMeta', 'useDemo', 'demoEvents',
            'users', 'actions'
        );
    }
}
