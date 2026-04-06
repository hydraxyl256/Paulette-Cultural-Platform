<?php

namespace App\Filament\Resources\ComicResource\Pages;

use App\Filament\Resources\ComicResource;
use App\Models\Comic;
use App\Models\Tribe;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ListComics extends ListRecords
{
    protected static string $resource = ComicResource::class;

    public function getView(): string
    {
        return 'filament.pages.comics-list';
    }

    // ── Filter state ─────────────────────────────────────────────
    public string $ckSearch       = '';
    public string $ckStatusFilter = '';
    public string $ckTribeFilter  = '';
    public string $ckAgeFilter    = '';
    public string $ckView         = 'grid'; // grid | table

    // ── Performance chart tab ────────────────────────────────────
    public string $ckChartTab = 'views';

    // ── Actions ──────────────────────────────────────────────────
    public function ckResetFilters(): void
    {
        $this->ckSearch       = '';
        $this->ckStatusFilter = '';
        $this->ckTribeFilter  = '';
        $this->ckAgeFilter    = '';
    }

    public function ckPublishComic(int $id): void
    {
        $comic = Comic::find($id);
        if ($comic && $comic->status !== 'published') {
            $comic->update(['status' => 'published']);
            Notification::make()->success()->title('Comic Published')
                ->body("{$comic->title} is now live.")->send();
        }
    }

    public function ckArchiveComic(int $id): void
    {
        $comic = Comic::find($id);
        if ($comic) {
            $comic->update(['status' => 'archived']);
            Notification::make()->warning()->title('Comic Archived')
                ->body("{$comic->title} has been archived.")->send();
        }
    }

    public function ckSetView(string $view): void
    {
        $this->ckView = $view;
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        $query = Comic::with(['tribe', 'organisation'])
            ->withCount('panels');

        if ($this->ckSearch) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->ckSearch}%")
                  ->orWhereHas('tribe', fn($tq) => $tq->where('name', 'like', "%{$this->ckSearch}%"));
            });
        }
        if ($this->ckStatusFilter) {
            $query->where('status', $this->ckStatusFilter);
        }
        if ($this->ckTribeFilter) {
            $query->where('tribe_id', $this->ckTribeFilter);
        }
        if ($this->ckAgeFilter) {
            [$min, $max] = explode('-', $this->ckAgeFilter);
            $query->where('age_min', '>=', (int)$min)->where('age_max', '<=', (int)$max);
        }

        $comics = $query->orderBy('created_at', 'desc')->get();

        // Stats
        $totalComics     = Comic::count();
        $publishedCount  = Comic::where('status', 'published')->count();
        $draftCount      = Comic::where('status', 'draft')->count();
        $reviewCount     = Comic::where('status', 'review')->count();

        // Progress events for views (use panels_count as proxy if no real analytics)
        $totalViews      = Comic::where('status', 'published')->sum('panels_count') * 1200;
        $totalDownloads  = intval($totalViews * 0.23);

        // Tribes for filter dropdown
        $tribes = Tribe::orderBy('name')->get(['id', 'name']);

        // Featured / spotlight comics (published, top 3)
        $featured = Comic::with(['tribe'])->withCount('panels')
            ->where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->take(2)->get();

        // Bar chart data (simulated monthly for current year)
        $chartData = collect(range(1, 12))->map(fn($m) => [
            'month'  => \Carbon\Carbon::create()->month($m)->format('M'),
            'views'  => Comic::where('status', 'published')
                ->whereMonth('created_at', $m)->count() * 1800 + rand(400, 2400),
            'reads'  => Comic::where('status', 'published')
                ->whereMonth('created_at', $m)->count() * 600 + rand(100, 900),
        ]);

        return [
            'comics'          => $comics,
            'featured'        => $featured,
            'totalComics'     => $totalComics,
            'publishedCount'  => $publishedCount,
            'draftCount'      => $draftCount,
            'reviewCount'     => $reviewCount,
            'totalViews'      => $totalViews,
            'totalDownloads'  => $totalDownloads,
            'tribes'          => $tribes,
            'chartData'       => $chartData,
            'createUrl'       => ComicResource::getUrl('create'),
        ];
    }
}
