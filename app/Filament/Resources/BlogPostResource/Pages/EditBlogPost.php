<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\BlogPostResource;
use App\Models\Article;
use App\Services\ArticleService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBlogPost extends EditRecord
{
    use HandlesMediaUploads;

    protected static string $resource = BlogPostResource::class;

    /** @var array<string, mixed> */
    protected array $pendingMedia = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Article $record */
        $record = $this->record;

        $data['tags'] = $record->tags->pluck('name')->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingMedia = [
            'featured' => $data['featured_image_temp'] ?? null,
            'gallery' => $data['gallery_temp'] ?? null,
            'video' => $data['video_temp'] ?? null,
        ];

        unset($data['featured_image_temp'], $data['gallery_temp'], $data['video_temp']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(ArticleService::class)->update($record, $data);
    }

    protected function afterSave(): void
    {
        /** @var Article $article */
        $article = $this->record;

        $this->persistMediaFromTempPath($article, $this->pendingMedia['featured'] ?? null, MediaCollection::Featured);
        $this->persistManyMediaFromTempPaths($article, $this->pendingMedia['gallery'] ?? null, MediaCollection::Gallery);
        $this->persistMediaFromTempPath($article, $this->pendingMedia['video'] ?? null, MediaCollection::Video);
    }
}
