<?php

namespace App\Filament\Concerns;

use App\Enums\MediaCollection;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Throwable;


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

        try {
            app(MediaService::class)->replace($record, $uploadedFile, $collection, $altText);
        } catch (Throwable $e) {
            report($e);
            $this->notifyMediaFailed();
        } finally {
            // The scratch copy is useless either way, so it is always removed.
            $disk->delete($tempPath);
        }
    }

    /**
     * @param  array<int, string>|null  $tempPaths
     */
    protected function persistManyMediaFromTempPaths(Model $record, ?array $tempPaths, MediaCollection $collection): void
    {
        $failed = false;

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

            try {
                app(MediaService::class)->attach($record, $uploadedFile, $collection);
            } catch (Throwable $e) {
                report($e);
                $failed = true;
            } finally {
                $disk->delete($tempPath);
            }
        }

        $failed && $this->notifyMediaFailed();
    }


    // Tells the editor the record saved but the media did not, so they can re-upload.
    protected function notifyMediaFailed(): void
    {
        Notification::make()
            ->title('Saved, but the media upload failed')
            ->body('Your changes were saved. Please re-upload the image or video and save again.')
            ->danger()
            ->persistent()
            ->send();
    }
}
