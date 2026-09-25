<?php

namespace App\Filament\Resources\NewsletterEditionResource\Pages;

use App\Filament\Resources\NewsletterEditionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterEditions extends ListRecords
{
    protected static string $resource = NewsletterEditionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}