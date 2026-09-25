<?php

namespace App\Filament\Resources\NewsletterEditionResource\Pages;

use App\Filament\Resources\NewsletterEditionResource;
use App\Services\NewsletterEditionService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditNewsletterEdition extends EditRecord
{
    protected static string $resource = NewsletterEditionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(NewsletterEditionService::class)->update($record, $data);
    }
}