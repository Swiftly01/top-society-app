<?php

namespace App\Filament\Resources\MagazineResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\MagazineResource;
use App\Services\MagazineService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMagazine extends CreateRecord
{
    use HandlesMediaUploads;

    protected static string $resource = MagazineResource::class;

    /** @var array<string, mixed> */
    protected array $pendingMedia = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingMedia = [
            'cover' => $data['cover_image_temp'] ?? null,
            'pdf' => $data['pdf_temp'] ?? null,
        ];

        unset($data['cover_image_temp'], $data['pdf_temp']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return app(MagazineService::class)->create($data);
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\Magazine $magazine */
        $magazine = $this->record;

        $this->persistMediaFromTempPath($magazine, $this->pendingMedia['cover'] ?? null, MediaCollection::Featured);
        $this->persistMediaFromTempPath($magazine, $this->pendingMedia['pdf'] ?? null, MediaCollection::Attachment);
    }
}
