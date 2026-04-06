<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use App\Models\AgeProfile;
use App\Models\ChildProfile;
use App\Filament\Resources\AgeProfileResource;
use Filament\Notifications\Notification;

class AgeProfiles extends Page
{
    // ── Page settings ─────────────────────────────────────────────
    protected string $view = 'filament.pages.age-profiles';

    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-user-group';
    protected static UnitEnum|string|null   $navigationGroup = 'PLATFORM';
    protected static ?string $slug            = 'age-profiles-cms'; // /admin/age-profiles taken by legacy web route
    protected static ?int    $navigationSort  = 2;
    protected static ?string $title           = 'Age Profiles';
    protected static ?string $navigationLabel = 'Age Profiles';

    // ── Livewire state ─────────────────────────────────────────────
    public string $ckSearch = '';

    // ── Design-layer meta per profile stage ───────────────────────
    public static function profileMeta(): array
    {
        return [
            'Early Years' => [
                'badge'       => 'EARLY YEARS',
                'color'       => '#059669',
                'accent'      => 'rgba(5,150,105,0.12)',
                'bar_color'   => '#f97316',   // orange
                'bar_pct'     => 25,
                'ui_label'    => 'Playful',
                'ui_icon'     => '🍪',
                'diff_label'  => 'Novice',
                'diff_tier'   => 1,
                'icon_svg'    => '<circle cx="12" cy="8" r="4"/><path d="M9 12c-.8.6-1.5 1.4-1.5 2.5C7.5 16.4 9.6 18 12 18s4.5-1.6 4.5-3.5c0-1.1-.7-1.9-1.5-2.5"/><path d="M9 8c0-.5.3-1 .8-1.2M15 8c0-.5-.3-1-.8-1.2"/>',
                'enrollment'  => 1284,
            ],
            'Foundation' => [
                'badge'       => 'FOUNDATION',
                'color'       => '#d97706',
                'accent'      => 'rgba(217,119,6,0.10)',
                'bar_color'   => '#059669',
                'bar_pct'     => 45,
                'ui_label'    => 'Friendly',
                'ui_icon'     => '😊',
                'diff_label'  => 'Intermediate',
                'diff_tier'   => 2,
                'icon_svg'    => '<rect x="4" y="14" width="16" height="2" rx="1"/><rect x="6" y="10" width="12" height="4" rx="1"/><path d="M8 10V7l4-3 4 3v3"/>',
                'enrollment'  => 4912,
            ],
            'Active Learning' => [
                'badge'       => 'ACTIVE LEARNING',
                'color'       => '#3b82f6',
                'accent'      => 'rgba(59,130,246,0.10)',
                'bar_color'   => '#3b82f6',
                'bar_pct'     => 70,
                'ui_label'    => 'Academic',
                'ui_icon'     => '🎓',
                'diff_label'  => 'Advanced',
                'diff_tier'   => 3,
                'icon_svg'    => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
                'enrollment'  => 8450,
            ],
            'Maturation' => [
                'badge'       => 'MATURATION',
                'color'       => '#374151',
                'accent'      => 'rgba(55,65,81,0.10)',
                'bar_color'   => '#111827',
                'bar_pct'     => 95,
                'ui_label'    => 'Sophisticated',
                'ui_icon'     => '⚙️',
                'diff_label'  => 'Expert',
                'diff_tier'   => 4,
                'icon_svg'    => '<circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>',
                'enrollment'  => 12190,
            ],
        ];
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        $profiles = AgeProfile::when($this->ckSearch, fn($q) => $q->where('stage', 'like', "%{$this->ckSearch}%"))
            ->orderBy('age_min')
            ->get();

        $meta = self::profileMeta();

        // Enrich with design-layer meta
        $enriched = $profiles->map(function ($p) use ($meta) {
            // Match by stage name (exact or fuzzy)
            $m = $meta[$p->stage]
                ?? collect($meta)->first(fn($v, $k) => str_contains(strtolower($p->stage), strtolower($k)))
                ?? ['badge' => strtoupper($p->stage), 'color' => '#059669', 'accent' => 'rgba(5,150,105,0.1)', 'bar_color' => '#059669', 'bar_pct' => 50, 'ui_label' => ucfirst($p->ui_mode), 'ui_icon' => '🎯', 'diff_label' => 'Level ' . $p->difficulty_ceiling, 'diff_tier' => $p->difficulty_ceiling, 'icon_svg' => '<circle cx="12" cy="12" r="8"/>', 'enrollment' => 0];

            $enrollment = $m['enrollment'] > 0 ? $m['enrollment'] : ($p->childProfiles()->count());

            return array_merge($m, [
                'id'             => $p->id,
                'stage'          => $p->stage,
                'age_min'        => $p->age_min,
                'age_max'        => $p->age_max,
                'ui_mode'        => $p->ui_mode,
                'difficulty'     => $p->difficulty_ceiling,
                'ageRange'       => $p->age_max ? "{$p->age_min}-{$p->age_max}" : "{$p->age_min}+",
                'enrollment'     => number_format($enrollment),
                'editUrl'        => AgeProfileResource::getUrl('edit', ['record' => $p->id]),
            ]);
        });

        $totalEnrolled = collect(self::profileMeta())->sum('enrollment');

        // Simulated config change log
        $configChanges = [
            ['when' => '2 HOURS AGO',  'source' => 'Age Profile 4-7',  'status' => 'SUCCESS',     'status_color' => '#059669', 'status_bg' => 'rgba(5,150,105,0.10)',  'text' => 'Updated <strong>Cognitive Difficulty</strong> tier from Level 2 to Level 3.', 'icon_type' => 'clock'],
            ['when' => 'YESTERDAY',    'source' => 'System Core',       'status' => 'SUCCESS',     'status_color' => '#059669', 'status_bg' => 'rgba(5,150,105,0.10)',  'text' => 'New UI Mode <strong>Sophisticated</strong> deployed for 13+ tier.',             'icon_type' => 'check'],
            ['when' => '2 DAYS AGO',   'source' => 'Age Profile 0-3',  'status' => 'INVESTIGATING','status_color' => '#d97706', 'status_bg' => 'rgba(217,119,6,0.12)',   'text' => 'Anomalous drop in engagement detected for <strong>Early Years</strong> segment.','icon_type' => 'alert'],
        ];

        $auditUrl = rescue(fn() => route('filament.admin.resources.audit-logs-management.index'), '/admin', false);
        $createUrl = AgeProfileResource::getUrl('create');

        return [
            'profiles'      => $enriched,
            'totalEnrolled' => number_format($totalEnrolled / 1000, 1) . 'k',
            'configChanges' => $configChanges,
            'auditUrl'      => $auditUrl,
            'createUrl'     => $createUrl,
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
