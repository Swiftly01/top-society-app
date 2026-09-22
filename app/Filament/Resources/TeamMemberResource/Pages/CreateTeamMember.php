<?php

namespace App\Filament\Resources\TeamMemberResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\TeamMemberResource;
use App\Services\TeamMemberService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeamMember extends CreateRecord
{
    use HandlesMediaUploads;

    protected static string $resource = TeamMemberResource::class;

    protected ?string $pendingPhoto = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingPhoto = $data['photo_temp'] ?? null;
        unset($data['photo_temp']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return app(TeamMemberService::class)->create($data);
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\TeamMember $member */
        $member = $this->record;

        $this->persistMediaFromTempPath($member, $this->pendingPhoto, MediaCollection::Featured);
    }
}