<?php

namespace App\Filament\Resources\FlashcardDeckResource\Pages;

use App\Filament\Resources\FlashcardDeckResource;
use App\Models\FlashcardDeck;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListFlashcardDecks extends ListRecords
{
    protected static string $resource = FlashcardDeckResource::class;

    public function getView(): string
    {
        return 'filament.pages.flashcards-list';
    }

    // ── UI state ─────────────────────────────────────────────────
    public string $ckSearch      = '';
    public string $ckStatus      = '';
    public string $ckTribe       = '';
    public int    $ckPage        = 1;
    public int    $ckPerPage     = 4;
    public string $ckSort        = 'updated_at';
    public string $ckSortDir     = 'desc';

    // ── Sorting ──────────────────────────────────────────────────
    public function ckSort(string $col): void
    {
        if ($this->ckSort === $col) {
            $this->ckSortDir = $this->ckSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->ckSort    = $col;
            $this->ckSortDir = 'asc';
        }
        $this->ckPage = 1;
    }

    public function ckSetPage(int $page): void
    {
        $this->ckPage = $page;
    }

    // ── Actions ──────────────────────────────────────────────────
    public function ckPublish(int $id): void
    {
        $deck = FlashcardDeck::find($id);
        if ($deck) {
            $deck->update(['status' => 'live']);
            Notification::make()->success()->title('Deck Published')
                ->body("{$deck->name} is now live.")->send();
        }
    }

    public function ckArchive(int $id): void
    {
        $deck = FlashcardDeck::find($id);
        if ($deck) {
            $deck->update(['status' => 'archived']);
            Notification::make()->warning()->title('Deck Archived')
                ->body("{$deck->name} has been archived.")->send();
        }
    }

    public function ckDelete(int $id): void
    {
        $deck = FlashcardDeck::find($id);
        if ($deck) {
            $name = $deck->name;
            $deck->delete();
            Notification::make()->danger()->title('Deck Deleted')
                ->body("{$name} was permanently deleted.")->send();
        }
    }

    // ── View data ─────────────────────────────────────────────────
    protected function getViewData(): array
    {
        // KPI stats
        $activeDecks     = FlashcardDeck::where('status', 'live')->count();
        $totalDecks      = FlashcardDeck::count();
        $totalCards      = \App\Models\Flashcard::count();
        $avgEngagement   = FlashcardDeck::where('status', 'live')->avg('engagement_rate') ?: 0;
        $engagementFormatted = number_format($avgEngagement / 100, 1) . '%';

        // Tribes for filter
        $tribes = \App\Models\Tribe::orderBy('name')->get(['id', 'name']);

        // Main query
        $query = FlashcardDeck::with(['tribe', 'organisation'])
            ->withCount('cards');

        if ($this->ckSearch) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->ckSearch}%")
                  ->orWhere('subtitle', 'like', "%{$this->ckSearch}%")
                  ->orWhereHas('tribe', fn($tq) => $tq->where('name', 'like', "%{$this->ckSearch}%"));
            });
        }
        if ($this->ckStatus) {
            $query->where('status', $this->ckStatus);
        }
        if ($this->ckTribe) {
            $query->where('tribe_id', $this->ckTribe);
        }

        // Sortable columns map
        $sortMap = [
            'name'       => 'name',
            'updated_at' => 'updated_at',
            'cards'      => 'cards_count',
        ];
        $sortCol = $sortMap[$this->ckSort] ?? 'updated_at';
        if ($sortCol === 'cards_count') {
            $query->orderBy('cards_count', $this->ckSortDir);
        } else {
            $query->orderBy($sortCol, $this->ckSortDir);
        }

        $allDecks   = $query->get();
        $totalCount = $allDecks->count();
        $totalPages = max(1, (int) ceil($totalCount / $this->ckPerPage));
        $page       = min($this->ckPage, $totalPages);
        $decks      = $allDecks->forPage($page, $this->ckPerPage);

        return [
            'decks'               => $decks,
            'totalCount'          => $totalCount,
            'totalPages'          => $totalPages,
            'currentPage'         => $page,
            'perPage'             => $this->ckPerPage,
            'activeDecks'         => $activeDecks,
            'totalCards'          => $totalCards,
            'engagementFormatted' => $engagementFormatted,
            'tribes'              => $tribes,
            'createUrl'           => FlashcardDeckResource::getUrl('create'),
        ];
    }
}
