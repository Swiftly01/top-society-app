<?php

namespace App\Filament\Resources\AdvertResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\AdvertResource;
use App\Services\AdvertService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAdvert extends EditRecord
{
    use HandlesMediaUploads;

    protected static string $resource = AdvertResource::class;

    protected ?string $pendingImage = null;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingImage = $data['image_temp'] ?? null;

        unset($data['image_temp']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(AdvertService::class)->update($record, $data);
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\Advert $advert */
        $advert = $this->record;

        $this->persistMediaFromTempPath($advert, $this->pendingImage, MediaCollection::Featured, $advert->title);
    }
}
