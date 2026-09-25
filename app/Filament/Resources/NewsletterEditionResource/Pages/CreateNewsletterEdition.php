<?php

namespace App\Filament\Resources\NewsletterEditionResource\Pages;

use App\Filament\Resources\NewsletterEditionResource;
use App\Services\NewsletterEditionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateNewsletterEdition extends CreateRecord
{
    protected static string $resource = NewsletterEditionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(NewsletterEditionService::class)->create($data);
    }
}