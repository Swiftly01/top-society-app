<?php

namespace App\Services;

use App\Models\Newsletter;
use App\Repositories\Contracts\NewsletterRepositoryInterface;
use Illuminate\Support\Str;

class NewsletterService
{
    public function __construct(protected NewsletterRepositoryInterface $newsletters) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Newsletter
    {
        $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $attributes['name']);

        if (! empty($attributes['is_default'])) {
            $this->clearOtherDefaults();
        }

        return $this->newsletters->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Newsletter $newsletter, array $attributes): Newsletter
    {
        if (array_key_exists('slug', $attributes) || array_key_exists('name', $attributes)) {
            $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $newsletter->name, $newsletter->id);
        }

        $newsletter = $this->newsletters->update($newsletter, $attributes);

        if ($newsletter->is_default) {
            $this->clearOtherDefaults($newsletter->id);
        }

        return $newsletter;
    }

    public function delete(Newsletter $newsletter): bool
    {
        return $this->newsletters->delete($newsletter);
    }

    /**
     * Same single-slot pattern as SponsoredFeatureService's is_featured
     * and MediaService's featured-image collection: only one newsletter
     * can be "the default" a generic subscribe form falls back to.
     */
    protected function clearOtherDefaults(?int $exceptId = null): void
    {
        $this->newsletters->activeOrdered()
            ->filter(fn (Newsletter $n) => $n->is_default && $n->id !== $exceptId)
            ->each(fn (Newsletter $n) => $this->newsletters->update($n, ['is_default' => false]));
    }

    protected function uniqueSlug(string $source, ?int $exceptId = null): string
    {
        $slug = Str::slug($source);
        $original = $slug;
        $suffix = 1;

        while ($this->newsletters->slugExists($slug, $exceptId)) {
            $slug = "{$original}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}