<?php

namespace App\Filament\Resources\MagazineResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\MagazineResource;
use App\Services\MagazineService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMagazine extends EditRecord
{
    use HandlesMediaUploads;

    protected static string $resource = MagazineResource::class;

    /** @var array<string, mixed> */
    protected array $pendingMedia = [];

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingMedia = [
            'cover' => $data['cover_image_temp'] ?? null,
            'pdf' => $data['pdf_temp'] ?? null,
        ];

        unset($data['cover_image_temp'], $data['pdf_temp']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(MagazineService::class)->update($record, $data);
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\Magazine $magazine */
        $magazine = $this->record;

        $this->persistMediaFromTempPath($magazine, $this->pendingMedia['cover'] ?? null, MediaCollection::Featured);
        $this->persistMediaFromTempPath($magazine, $this->pendingMedia['pdf'] ?? null, MediaCollection::Attachment);
    }
}
