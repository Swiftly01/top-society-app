<?php

namespace App\Services;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Support\Str;

class TagService
{
    public function __construct(protected TagRepositoryInterface $tags) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Tag
    {
        $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $attributes['name']);

        return $this->tags->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Tag $tag, array $attributes): Tag
    {
        if (array_key_exists('slug', $attributes) || array_key_exists('name', $attributes)) {
            $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $tag->name, $tag->id);
        }

        return $this->tags->update($tag, $attributes);
    }

    public function delete(Tag $tag): bool
    {
        return $this->tags->delete($tag);
    }

    protected function uniqueSlug(string $source, ?int $exceptId = null): string
    {
        $slug = Str::slug($source);
        $original = $slug;
        $suffix = 1;

        while ($this->tags->slugExists($slug, $exceptId)) {
            $slug = "{$original}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
