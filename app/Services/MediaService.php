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

    /**
     * Upload a file and attach it to a model in one step.
     *
     * For `MediaCollection::Featured` (a single-slot collection), any
     * existing featured media is deleted first — both the Supabase file
     * and the database row — so a model never accidentally ends up with
     * two "featured" images.
     */
    public function attach(Model $mediable, UploadedFile $file, MediaCollection $collection, ?string $altText = null): Media
    {
        if ($collection === MediaCollection::Featured) {
            $this->clearCollection($mediable, $collection);
        }

        $directory = $this->directoryFor($mediable, $collection);
        $uploaded = $this->storage->upload($file, $directory);

        return DB::transaction(fn () => $this->mediaRepository->create([
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

    /**
     * Delete a single media item — from Supabase first, then the database
     * row. Ordering matters: if the DB delete failed after a successful
     * file delete we'd have a dangling record pointing at nothing, which
     * is a strictly easier problem to notice and fix (a 404'd image) than
     * an orphaned file silently costing storage forever.
     */
    public function delete(Media $media): bool
    {
        $this->storage->delete($media->path);

        return $this->mediaRepository->delete($media);
    }

    /**
     * Replace whatever's in a collection with the given file — the
     * "featured image" picker calls this directly rather than composing
     * clearCollection() + attach() itself.
     */
    public function replace(Model $mediable, UploadedFile $file, MediaCollection $collection, ?string $altText = null): Media
    {
        $this->clearCollection($mediable, $collection);

        return $this->attach($mediable, $file, $collection, $altText);
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
