<?php

namespace App\Services;

use App\Enums\MediaCollection;
use App\Models\Media;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Services\Contracts\MediaStorageInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class MediaService
{
    public function __construct(
        protected MediaStorageInterface $storage,
        protected MediaRepositoryInterface $mediaRepository,
    ) {}


    public function attach(Model $mediable, UploadedFile $file, MediaCollection $collection, ?string $altText = null): Media
    {
        if ($collection === MediaCollection::Featured) {
            $this->clearCollection($mediable, $collection);
        }

        return $this->store($mediable, $file, $collection, $altText);
    }

    public function replace(Model $mediable, UploadedFile $file, MediaCollection $collection, ?string $altText = null): Media
    {
        $previous = $this->mediaRepository->findForMediableAndCollection($mediable, $collection);

        // Upload first, so a failed upload leaves the old image untouched.
        $media = $this->store($mediable, $file, $collection, $altText);

        foreach ($previous as $item) {
            $this->delete($item);
        }

        return $media;
    }

    // Uploads the file and records it, without touching existing media.
    protected function store(Model $mediable, UploadedFile $file, MediaCollection $collection, ?string $altText = null): Media
    {
        $directory = $this->directoryFor($mediable, $collection);
        $uploaded = $this->storage->upload($file, $directory);

        return DB::transaction(fn() => $this->mediaRepository->create([
            'mediable_type' => $mediable->getMorphClass(),
            'mediable_id' => $mediable->getKey(),
            'collection' => $collection->value,
            'disk' => config('media.disk', 'supabase'),
            'path' => $uploaded['path'],
            'url' => $uploaded['url'],
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?? $file->getClientMimeType(),
            'size' => $file->getSize(),
            'alt_text' => $altText,
            'order' => $this->mediaRepository->forMediable($mediable, $collection)->count(),
        ]));
    }

    
    public function delete(Media $media): bool
    {
        $this->storage->delete($media->path);

        return $this->mediaRepository->delete($media);
    }


    /**
     * @param  array<int, int>  $orderedIds
     */
    public function reorder(Model $mediable, MediaCollection $collection, array $orderedIds): void
    {
        $items = $this->mediaRepository->findForMediableAndCollection($mediable, $collection);

        foreach ($orderedIds as $position => $id) {
            $item = $items->firstWhere('id', $id);

            if ($item) {
                $this->mediaRepository->update($item, ['order' => $position]);
            }
        }
    }

    protected function clearCollection(Model $mediable, MediaCollection $collection): void
    {
        $existing = $this->mediaRepository->findForMediableAndCollection($mediable, $collection);

        foreach ($existing as $item) {
            $this->delete($item);
        }
    }

    protected function directoryFor(Model $mediable, MediaCollection $collection): string
    {
        $root = config('media.root_directory', 'articles');
        $type = strtolower(class_basename($mediable));

        return "{$root}/{$type}/{$mediable->getKey()}/{$collection->value}";
    }
}
