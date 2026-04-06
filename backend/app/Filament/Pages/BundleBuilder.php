<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use App\Models\ContentBundle;
use App\Models\Comic;
use App\Models\AudioTrack;
use App\Models\FlashcardDeck;
use App\Models\Tribe;
use App\Filament\Resources\ContentBundleResource;
use Filament\Notifications\Notification;

class BundleBuilder extends Page
{
    // ── Page settings ─────────────────────────────────────────────
    protected string $view = 'filament.pages.bundle-builder';

    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-cube';
    protected static UnitEnum|string|null   $navigationGroup = 'CONTENT';
    protected static ?int    $navigationSort  = 4;
    protected static ?string $title           = 'Bundle Builder';
    protected static ?string $navigationLabel = 'Bundle Builder';

    // ── Builder Livewire state ─────────────────────────────────────
    public string $ckTitle           = '';
    public string $ckTribeId         = '';
    public string $ckAgeRange        = '3 - 6 Years';
    public string $ckVersion         = 'v2.4.1';
    public bool   $ckEncryption      = true;
    public array  $ckSelectedIds     = [];
    public string $ckAssetTab        = 'all';
    public ?int   $ckEditingBundleId = null;

    // ── Internal helpers ──────────────────────────────────────────
    protected function computeBundleSize(): int
    {
        $total = 0;
        foreach ($this->ckSelectedIds as $assetKey) {
            [$type, $id] = explode('_', $assetKey, 2);
            $total += match ($type) {
                'comic' => 45 * 1024 * 1024,
                'audio' => AudioTrack::find((int)$id)?->file_size_bytes ?? (22 * 1024 * 1024),
                'deck'  => 5 * 1024 * 1024,
                default => 0,
            };
        }
        return $total;
    }

    // ── Toggle asset selection ────────────────────────────────────
    public function ckToggleAsset(string $assetKey): void
    {
        if (in_array($assetKey, $this->ckSelectedIds)) {
            $this->ckSelectedIds = array_values(
                array_filter($this->ckSelectedIds, fn($k) => $k !== $assetKey)
            );
        } else {
            $this->ckSelectedIds[] = $assetKey;
        }
    }

    // ── Save draft ────────────────────────────────────────────────
    public function ckSaveDraft(): void
    {
        if (empty(trim($this->ckTitle))) {
            Notification::make()->warning()->title('Bundle title required')->send();
            return;
        }
        $bundle = ContentBundle::updateOrCreate(
            ['id' => $this->ckEditingBundleId],
            [
                'title'               => $this->ckTitle,
                'tribe_id'            => $this->ckTribeId ?: null,
                'age_range'           => $this->ckAgeRange,
                'deployment_version'  => $this->ckVersion,
                'encryption_enabled'  => $this->ckEncryption,
                'status'              => 'draft',
                'bundle_size_bytes'   => $this->computeBundleSize(),
                'build_readiness_pct' => min(100, count($this->ckSelectedIds) * 8),
                'selected_asset_ids'  => $this->ckSelectedIds,
            ]
        );
        $this->ckEditingBundleId = $bundle->id;
        Notification::make()->success()->title('Draft Saved')
            ->body("Bundle '{$this->ckTitle}' saved.")->send();
    }

    // ── Build & Ship ──────────────────────────────────────────────
    public function ckBuildAndShip(): void
    {
        if (empty(trim($this->ckTitle))) {
            Notification::make()->warning()->title('Bundle title required')->send();
            return;
        }
        if (empty($this->ckSelectedIds)) {
            Notification::make()->warning()->title('No assets selected')
                ->body('Select at least one asset before shipping.')->send();
            return;
        }
        $bundle = ContentBundle::updateOrCreate(
            ['id' => $this->ckEditingBundleId],
            [
                'title'               => $this->ckTitle,
                'tribe_id'            => $this->ckTribeId ?: null,
                'age_range'           => $this->ckAgeRange,
                'deployment_version'  => $this->ckVersion,
                'encryption_enabled'  => $this->ckEncryption,
                'status'              => 'building',
                'bundle_size_bytes'   => $this->computeBundleSize(),
                'build_readiness_pct' => 100,
                'selected_asset_ids'  => $this->ckSelectedIds,
                'bandwidth_mbps'      => rand(50, 200),
            ]
        );
        $this->ckEditingBundleId = $bundle->id;
        Notification::make()->success()
            ->title('🚀 Bundle Shipped!')
            ->body("'{$this->ckTitle}' is now building and will deploy shortly.")
            ->send();
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        $tribes = Tribe::orderBy('name')->get(['id', 'name']);

        $comics = Comic::with('tribe')->get()->map(fn($c) => [
            'key'     => 'comic_' . $c->id,
            'name'    => $c->title,
            'size'    => '12.4 MB',
            'quality' => 'High Res',
            'type'    => 'comic',
            'status'  => $c->status,
            'icon'    => '📖',
        ]);

        $audio = AudioTrack::with('tribe')->get()->map(fn($a) => [
            'key'     => 'audio_' . $a->id,
            'name'    => $a->title,
            'size'    => method_exists($a, 'formattedFileSize') ? $a->formattedFileSize() : '22 MB',
            'quality' => 'Lossless',
            'type'    => 'audio',
            'status'  => $a->status,
            'icon'    => '🎵',
        ]);

        $decks = FlashcardDeck::with('tribe')->get()->map(fn($d) => [
            'key'     => 'deck_' . $d->id,
            'name'    => $d->name,
            'size'    => '4.2 MB',
            'quality' => 'Interactive',
            'type'    => 'deck',
            'status'  => $d->status,
            'icon'    => '🃏',
        ]);

        $selectedCount = count($this->ckSelectedIds);
        $bundleBytes   = $this->computeBundleSize();
        $bundleGB      = $bundleBytes >= 1073741824
            ? number_format($bundleBytes / 1073741824, 2)
            : ($bundleBytes > 0 ? number_format($bundleBytes / 1048576, 0) : '0');
        $bundleUnit    = $bundleBytes >= 1073741824 ? 'GB' : 'MB';
        $readinessPct  = min(100, $selectedCount > 0 ? max(20, $selectedCount * 8) : 0);
        if ($this->ckTitle) $readinessPct = min(100, $readinessPct + 10);

        $recentBundles = ContentBundle::with('tribe')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

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

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
