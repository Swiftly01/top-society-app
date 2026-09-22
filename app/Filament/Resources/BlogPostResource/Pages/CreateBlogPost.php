<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Enums\MediaCollection;
use App\Filament\Concerns\HandlesMediaUploads;
use App\Filament\Resources\BlogPostResource;
use App\Services\ArticleService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBlogPost extends CreateRecord
{
    use HandlesMediaUploads;

    protected static string $resource = BlogPostResource::class;

    /** @var array<string, mixed> */
    protected array $pendingMedia = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingMedia = [
            'featured' => $data['featured_image_temp'] ?? null,
            'gallery' => $data['gallery_temp'] ?? null,
            'video' => $data['video_temp'] ?? null,
        ];

        unset($data['featured_image_temp'], $data['gallery_temp'], $data['video_temp']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return app(ArticleService::class)->create($data);
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\Article $article */
        $article = $this->record;

        $this->persistMediaFromTempPath($article, $this->pendingMedia['featured'] ?? null, MediaCollection::Featured);
        $this->persistManyMediaFromTempPaths($article, $this->pendingMedia['gallery'] ?? null, MediaCollection::Gallery);
        $this->persistMediaFromTempPath($article, $this->pendingMedia['video'] ?? null, MediaCollection::Video);
    }
}
