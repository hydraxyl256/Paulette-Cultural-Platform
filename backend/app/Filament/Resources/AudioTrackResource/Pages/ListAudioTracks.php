<?php

namespace App\Filament\Resources\AudioTrackResource\Pages;

use App\Filament\Resources\AudioTrackResource;
use App\Models\AudioTrack;
use App\Models\Tribe;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListAudioTracks extends ListRecords
{
    protected static string $resource = AudioTrackResource::class;

    public function getView(): string
    {
        return 'filament.pages.songs-list';
    }

    // ── UI state ─────────────────────────────────────────────────
    public string $ckSearch      = '';
    public string $ckTab         = 'all';      // all | popular | recent | byTribe
    public string $ckTribeFilter = '';
    public string $ckView        = 'grid';     // grid | list
    public int    $ckPerPage     = 12;

    // ── Actions ──────────────────────────────────────────────────
    public function ckSetTab(string $tab): void
    {
        $this->ckTab = $tab;
        $this->ckPerPage = 12;
    }

    public function ckSetView(string $view): void
    {
        $this->ckView = $view;
    }

    public function ckLoadMore(): void
    {
        $this->ckPerPage += 12;
    }

    public function ckArchive(int $id): void
    {
        $track = AudioTrack::find($id);
        if ($track) {
            $track->update(['status' => 'archived']);
            Notification::make()->warning()->title('Track Archived')
                ->body("{$track->title} has been archived.")->send();
        }
    }

    public function ckMakeLive(int $id): void
    {
        $track = AudioTrack::find($id);
        if ($track) {
            $track->update(['status' => 'live']);
            Notification::make()->success()->title('Track is Live')
                ->body("{$track->title} is now live.")->send();
        }
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        // ── KPI stats ────────────────────────────────────────────
        $totalTracks      = AudioTrack::count();
        $liveCount        = AudioTrack::where('status', 'live')->count();
        $streamingOrgs    = AudioTrack::distinct('org_id')->whereNotNull('org_id')->count('org_id');
        $avgDuration      = AudioTrack::avg('duration_seconds') ?: 0;
        $totalSizeBytes   = AudioTrack::sum('file_size_bytes');
        $maxSizeBytes     = 12 * 1024 * 1024 * 1024; // 12 GB cap for percentage
        $storageUsedPct   = $maxSizeBytes > 0 ? min(round(($totalSizeBytes / $maxSizeBytes) * 100), 100) : 0;

        // Format avg playtime like "18m 42s"
        $avgMin  = intdiv((int)$avgDuration, 60);
        $avgSec  = (int)$avgDuration % 60;
        $avgPlaytime = $avgMin > 0 ? "{$avgMin}m {$avgSec}s" : "{$avgSec}s";

        // Format storage
        $storageGB = $totalSizeBytes >= 1073741824
            ? round($totalSizeBytes / 1073741824, 1) . ' GB'
            : round($totalSizeBytes / 1048576, 1) . ' MB';

        // ── Query ────────────────────────────────────────────────
        $query = AudioTrack::with(['tribe', 'organisation']);

        if ($this->ckSearch) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->ckSearch}%")
                  ->orWhere('subtitle', 'like', "%{$this->ckSearch}%")
                  ->orWhere('category', 'like', "%{$this->ckSearch}%")
                  ->orWhereHas('tribe', fn($tq) => $tq->where('name', 'like', "%{$this->ckSearch}%"));
            });
        }

        if ($this->ckTribeFilter) {
            $query->where('tribe_id', $this->ckTribeFilter);
        }

        switch ($this->ckTab) {
            case 'popular':
                $query->orderBy('play_count', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'byTribe':
                $query->orderBy('tribe_id')->orderBy('title');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $allTracks = $query->get();
        $tracks    = $allTracks->take($this->ckPerPage);

        // Tribes for filter
        $tribes = Tribe::orderBy('name')->get(['id', 'name']);

        return [
            'tracks'         => $tracks,
            'totalTracks'    => $totalTracks,
            'totalCount'     => $allTracks->count(),
            'liveCount'      => $liveCount,
            'streamingOrgs'  => $streamingOrgs ?: 0,
            'avgPlaytime'    => $avgPlaytime,
            'storageGB'      => $storageGB,
            'storageUsedPct' => $storageUsedPct,
            'tribes'         => $tribes,
            'createUrl'      => AudioTrackResource::getUrl('create'),
        ];
    }
}
