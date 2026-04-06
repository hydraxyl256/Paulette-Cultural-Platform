<?php

namespace App\Filament\Resources\RbacRoleResource\Pages;

use App\Filament\Resources\RbacRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRbacRole extends CreateRecord
{
    protected static string $resource = RbacRoleResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
