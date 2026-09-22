<?php

namespace App\Filament\Concerns;

use App\Enums\MediaCollection;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Filament's FileUpload component, configured with disk('local'), stores
 * files to a local scratch disk as part of normal form processing (before
 * handleRecordCreation/handleRecordUpdate even runs) and hands back a
 * relative path string. This trait picks that file back up, hands it to
 * MediaService — the same service any other part of the app would use —
 * so the *actual* Supabase upload happens in exactly one place, then
 * deletes the local scratch copy.
 *
 * This is intentionally a bit more roundabout than pointing FileUpload
 * directly at the "supabase" disk, in exchange for MediaService (not
 * Filament) staying the single source of truth for what happens on
 * upload: which Media row gets created, single-slot collections getting
 * cleared, etc.
 */
trait HandlesMediaUploads
{
    protected function persistMediaFromTempPath(Model $record, ?string $tempPath, MediaCollection $collection, ?string $altText = null): void
    {
        if (! $tempPath) {
            return;
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($tempPath)) {
            return;
        }

        $uploadedFile = new UploadedFile(
            $disk->path($tempPath),
            basename($tempPath),
            $disk->mimeType($tempPath) ?: null,
            null,
            true, // $test — skips is_uploaded_file() checks, since this isn't a real HTTP upload
        );

        app(MediaService::class)->replace($record, $uploadedFile, $collection, $altText);

        $disk->delete($tempPath);
    }

    /**
     * @param  array<int, string>|null  $tempPaths
     */
    protected function persistManyMediaFromTempPaths(Model $record, ?array $tempPaths, MediaCollection $collection): void
    {
        foreach ($tempPaths ?? [] as $tempPath) {
            $disk = Storage::disk('local');

            if (! $disk->exists($tempPath)) {
                continue;
            }

            $uploadedFile = new UploadedFile(
                $disk->path($tempPath),
                basename($tempPath),
                $disk->mimeType($tempPath) ?: null,
                null,
                true,
            );

            app(MediaService::class)->attach($record, $uploadedFile, $collection);
            $disk->delete($tempPath);
        }
    }
}
