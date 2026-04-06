<?php

namespace App\Filament\Resources\FlashcardDeckResource\Pages;

use App\Filament\Resources\FlashcardDeckResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFlashcardDeck extends CreateRecord
{
    protected static string $resource = FlashcardDeckResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
