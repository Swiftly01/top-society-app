<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface MediaStorageInterface
{
    /**
     * Upload a file and return its stored path plus a URL to serve it.
     *
     * @return array{path: string, url: string}
     */
    public function upload(UploadedFile $file, string $directory): array;

    public function delete(string $path): bool;

    public function url(string $path): string;
}
