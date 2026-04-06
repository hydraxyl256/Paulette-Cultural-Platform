<?php

namespace App\Filament\Resources\ContentBundleResource\Pages;

use App\Filament\Resources\ContentBundleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentBundle extends CreateRecord
{
    protected static string $resource = ContentBundleResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
