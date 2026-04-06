<?php

namespace App\Filament\Resources\ContentBundleResource\Pages;

use App\Filament\Resources\ContentBundleResource;
use Filament\Resources\Pages\EditRecord;

class EditContentBundle extends EditRecord
{
    protected static string $resource = ContentBundleResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
