<?php

namespace App\Services\Storage;

use App\Services\Contracts\MediaStorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class SupabaseStorageService implements MediaStorageInterface
{
    protected string $disk;

    public function __construct(?string $disk = null)
    {
        $this->disk = $disk ?? config('media.disk', 'supabase');
    }

    /**
     * @return array{path: string, url: string}
     */
    public function upload(UploadedFile $file, string $directory): array
    {
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $path = trim($directory, '/').'/'.$filename;

        try {
            Storage::disk($this->disk)->putFileAs(
                dirname($path),
                $file,
                basename($path),
                ['visibility' => 'public'],
            );
        } catch (\Throwable $e) {
            Log::error('Supabase upload failed', ['path' => $path, 'error' => $e->getMessage()]);

            throw $e;
        }

        return [
            'path' => $path,
            'url' => $this->url($path),
        ];
    }

    public function delete(string $path): bool
    {
        try {
            return Storage::disk($this->disk)->delete($path);
        } catch (\Throwable $e) {
            Log::error('Supabase delete failed', ['path' => $path, 'error' => $e->getMessage()]);

            return false;
        }
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }
}
