<?php

namespace App\Filament\Resources\SponsoredFeatureResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\SponsoredFeatureResource;
use App\Services\SponsoredFeatureService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSponsoredFeature extends EditRecord
{
    use HandlesMediaUploads;

    protected static string $resource = SponsoredFeatureResource::class;

    /** @var array<string, mixed> */
    protected array $pendingMedia = [];

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingMedia = [
            'featured' => $data['featured_image_temp'] ?? null,
            'video' => $data['video_temp'] ?? null,
        ];

        unset($data['featured_image_temp'], $data['video_temp']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(SponsoredFeatureService::class)->update($record, $data);
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\SponsoredFeature $feature */
        $feature = $this->record;

        $this->persistMediaFromTempPath($feature, $this->pendingMedia['featured'] ?? null, MediaCollection::Featured);
        $this->persistMediaFromTempPath($feature, $this->pendingMedia['video'] ?? null, MediaCollection::Video);
    }
}
