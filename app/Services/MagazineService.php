<?php

namespace App\Services;

use App\Models\Magazine;
use App\Repositories\Contracts\MagazineRepositoryInterface;
use Illuminate\Support\Str;

class MagazineService
{
    public function __construct(protected MagazineRepositoryInterface $magazines) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Magazine
    {
        $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $attributes['title']);

        return $this->magazines->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Magazine $magazine, array $attributes): Magazine
    {
        if (array_key_exists('slug', $attributes) || array_key_exists('title', $attributes)) {
            $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $magazine->title, $magazine->id);
        }

        return $this->magazines->update($magazine, $attributes);
    }

    public function delete(Magazine $magazine): bool
    {
        return $this->magazines->delete($magazine);
    }

    public function recordDownload(Magazine $magazine): void
    {
        $this->magazines->incrementDownloads($magazine);
    }

    protected function uniqueSlug(string $source, ?int $exceptId = null): string
    {
        $slug = Str::slug($source);
        $original = $slug;
        $suffix = 1;

        while ($this->magazines->slugExists($slug, $exceptId)) {
            $slug = "{$original}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
