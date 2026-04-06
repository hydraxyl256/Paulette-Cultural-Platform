<?php

namespace App\Filament\Resources\ContentBundleResource\Pages;

use App\Filament\Resources\ContentBundleResource;
use App\Models\ContentBundle;
use App\Models\Comic;
use App\Models\AudioTrack;
use App\Models\FlashcardDeck;
use App\Models\Tribe;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListContentBundles extends ListRecords
{
    protected static string $resource = ContentBundleResource::class;

    public function getView(): string
    {
        return 'filament.pages.bundle-builder';
    }

    // ── Builder state ─────────────────────────────────────────────
    public string $ckTitle            = '';
    public string $ckTribeId          = '';
    public string $ckAgeRange         = '3 - 6 Years';
    public string $ckVersion          = 'v2.4.1';
    public bool   $ckEncryption       = true;
    public array  $ckSelectedIds      = [];   // selected asset IDs (prefixed: comic_1, audio_3, deck_2)
    public string $ckAssetTab         = 'all'; // all | selected
    public ?int   $ckEditingBundleId  = null;

    // ── Computed bundle size (bytes) ──────────────────────────────
    protected function computeBundleSize(): int
    {
        $total = 0;
        foreach ($this->ckSelectedIds as $assetKey) {
            [$type, $id] = explode('_', $assetKey, 2);
            $total += match ($type) {
                'comic' => 45 * 1024 * 1024,  // ~45 MB per comic
                'audio' => AudioTrack::find((int)$id)?->file_size_bytes ?? (22 * 1024 * 1024),
                'deck'  => 5 * 1024 * 1024,   // ~5 MB per deck
                default => 0,
            };
        }
        return $total;
    }

    // ── Toggle asset selection ────────────────────────────────────
    public function ckToggleAsset(string $assetKey): void
    {
        if (in_array($assetKey, $this->ckSelectedIds)) {
            $this->ckSelectedIds = array_values(array_filter(
                $this->ckSelectedIds, fn($k) => $k !== $assetKey
            ));
        } else {
            $this->ckSelectedIds[] = $assetKey;
        }
    }

    // ── Save draft ───────────────────────────────────────────────
    public function ckSaveDraft(): void
    {
        if (empty(trim($this->ckTitle))) {
            Notification::make()->warning()->title('Bundle title required')->send();
            return;
        }
        $sizeBytes = $this->computeBundleSize();
        $readiness = min(100, count($this->ckSelectedIds) * 8);

        $bundle = ContentBundle::updateOrCreate(
            ['id' => $this->ckEditingBundleId],
            [
                'title'               => $this->ckTitle,
                'tribe_id'            => $this->ckTribeId ?: null,
                'age_range'           => $this->ckAgeRange,
                'deployment_version'  => $this->ckVersion,
                'encryption_enabled'  => $this->ckEncryption,
                'status'              => 'draft',
                'bundle_size_bytes'   => $sizeBytes,
                'build_readiness_pct' => $readiness,
                'selected_asset_ids'  => $this->ckSelectedIds,
            ]
        );
        $this->ckEditingBundleId = $bundle->id;
        Notification::make()->success()->title('Draft Saved')
            ->body("Bundle '{$this->ckTitle}' saved as draft.")->send();
    }

    // ── Build & ship ─────────────────────────────────────────────
    public function ckBuildAndShip(): void
    {
        if (empty(trim($this->ckTitle))) {
            Notification::make()->warning()->title('Bundle title required')->send();
            return;
        }
        if (empty($this->ckSelectedIds)) {
            Notification::make()->warning()->title('No assets selected')
                ->body('Select at least one asset to build a bundle.')->send();
            return;
        }
        $sizeBytes = $this->computeBundleSize();

        $bundle = ContentBundle::updateOrCreate(
            ['id' => $this->ckEditingBundleId],
            [
                'title'               => $this->ckTitle,
                'tribe_id'            => $this->ckTribeId ?: null,
                'age_range'           => $this->ckAgeRange,
                'deployment_version'  => $this->ckVersion,
                'encryption_enabled'  => $this->ckEncryption,
                'status'              => 'building',
                'bundle_size_bytes'   => $sizeBytes,
                'build_readiness_pct' => 100,
                'selected_asset_ids'  => $this->ckSelectedIds,
                'bandwidth_mbps'      => rand(50, 200),
            ]
        );
        $this->ckEditingBundleId = $bundle->id;
        Notification::make()->success()
            ->title('🚀 Bundle Shipped!')
            ->body("'{$this->ckTitle}' is now building and will be deployed shortly.")
            ->send();
    }

    // ── Reset builder ─────────────────────────────────────────────
    public function ckReset(): void
    {
        $this->ckTitle           = '';
        $this->ckTribeId         = '';
        $this->ckAgeRange        = '3 - 6 Years';
        $this->ckVersion         = 'v2.4.1';
        $this->ckEncryption      = true;
        $this->ckSelectedIds     = [];
        $this->ckEditingBundleId = null;
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        $tribes = Tribe::orderBy('name')->get(['id', 'name']);

        // Build asset library: grouped by type
        $comics = Comic::with('tribe')->get()->map(fn($c) => [
            'key'      => 'comic_' . $c->id,
            'name'     => $c->title,
            'subtitle' => $c->tribe?->name ? $c->tribe->name . ' Tribe' : null,
            'size'     => '12.4 MB',
            'quality'  => 'High Res',
            'type'     => 'comic',
            'status'   => $c->status,
            'icon'     => '📖',
        ]);

        $audio = AudioTrack::with('tribe')->get()->map(fn($a) => [
            'key'      => 'audio_' . $a->id,
            'name'     => $a->title,
            'subtitle' => $a->subtitle,
            'size'     => $a->formattedFileSize(),
            'quality'  => match($a->category ?? '') {
                'nature_ambience' => 'Lossless',
                'drumming'        => 'Optimized',
                default           => 'Compressed',
            },
            'type'     => 'audio',
            'status'   => $a->status,
            'icon'     => '🎵',
        ]);

        $decks = FlashcardDeck::with('tribe')->get()->map(fn($d) => [
            'key'      => 'deck_' . $d->id,
            'name'     => $d->name,
            'subtitle' => $d->subtitle,
            'size'     => '4.2 MB',
            'quality'  => 'Interactive',
            'type'     => 'deck',
            'status'   => $d->status,
            'icon'     => '🃏',
        ]);

        // Bundle size and readiness
        $selectedCount = count($this->ckSelectedIds);
        $bundleBytes   = $this->computeBundleSize();
        $bundleGB      = $bundleBytes >= 1073741824
            ? number_format($bundleBytes / 1073741824, 2)
            : number_format($bundleBytes / 1048576, 0);
        $bundleUnit    = $bundleBytes >= 1073741824 ? 'GB' : 'MB';
        $readinessPct  = min(100, $selectedCount > 0 ? max(20, $selectedCount * 8) : 0);
        if ($this->ckTitle) $readinessPct = min(100, $readinessPct + 10);
        if ($this->ckTribeId) $readinessPct = min(100, $readinessPct + 5);

        // Recent bundles (sidebar history)
        $recentBundles = ContentBundle::with('tribe')
            ->orderBy('updated_at', 'desc')
            ->take(5)->get();

        return [
            'tribes'        => $tribes,
            'comics'        => $comics,
            'audio'         => $audio,
            'decks'         => $decks,
            'selectedCount' => $selectedCount,
            'bundleGB'      => $bundleGB,
            'bundleUnit'    => $bundleUnit,
            'readinessPct'  => $readinessPct,
            'recentBundles' => $recentBundles,
            'createUrl'     => ContentBundleResource::getUrl('create'),
        ];
    }
}
