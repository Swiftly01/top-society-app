<?php

namespace App\Filament\Resources\AdvertResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\AdvertResource;
use App\Services\AdvertService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAdvert extends CreateRecord
{
    use HandlesMediaUploads;

    protected static string $resource = AdvertResource::class;

    protected ?string $pendingImage = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingImage = $data['image_temp'] ?? null;

        unset($data['image_temp']);

        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return app(AdvertService::class)->create($data);
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\Advert $advert */
        $advert = $this->record;

        $this->persistMediaFromTempPath($advert, $this->pendingImage, MediaCollection::Featured, $advert->title);
    }
}
