<?php

namespace App\Filament\Resources\TeamMemberResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\TeamMemberResource;
use App\Services\TeamMemberService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeamMember extends EditRecord
{
    use HandlesMediaUploads;

    protected static string $resource = TeamMemberResource::class;

    protected ?string $pendingPhoto = null;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingPhoto = $data['photo_temp'] ?? null;
        unset($data['photo_temp']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(TeamMemberService::class)->update($record, $data);
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\TeamMember $member */
        $member = $this->record;

        $this->persistMediaFromTempPath($member, $this->pendingPhoto, MediaCollection::Featured);
    }
}