<?php

namespace App\Filament\Resources\FlashcardDeckResource\Pages;

use App\Filament\Resources\FlashcardDeckResource;
use Filament\Resources\Pages\EditRecord;

class EditFlashcardDeck extends EditRecord
{
    protected static string $resource = FlashcardDeckResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
