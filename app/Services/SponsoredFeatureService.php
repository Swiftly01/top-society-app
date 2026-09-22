<?php

namespace App\Services;

use App\Models\SponsoredFeature;
use App\Repositories\Contracts\SponsoredFeatureRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SponsoredFeatureService
{
    public function __construct(protected SponsoredFeatureRepositoryInterface $sponsoredFeatures) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): SponsoredFeature
    {
        $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $attributes['title']);

        return DB::transaction(function () use ($attributes) {
            $feature = $this->sponsoredFeatures->create($attributes);

            if ($feature->is_featured) {
                $this->unfeatureOthers($feature);
            }

            return $feature;
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(SponsoredFeature $feature, array $attributes): SponsoredFeature
    {
        if (array_key_exists('slug', $attributes) || array_key_exists('title', $attributes)) {
            $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $feature->title, $feature->id);
        }

        return DB::transaction(function () use ($feature, $attributes) {
            $feature = $this->sponsoredFeatures->update($feature, $attributes);

            if ($feature->is_featured) {
                $this->unfeatureOthers($feature);
            }

            return $feature;
        });
    }

    public function delete(SponsoredFeature $feature): bool
    {
        return $this->sponsoredFeatures->delete($feature);
    }

    /**
     * Only one placement occupies the homepage's large featured slot at a
     * time. Rather than validating "is another item already featured?" as
     * a form error (annoying — the admin would have to go unfeature the
     * old one first, in a separate trip), marking a new item featured
     * here silently demotes whichever one currently holds the slot. This
     * mirrors how MediaService clears a single-slot media collection
     * (e.g. `featured` image) before attaching a new one.
     */
    protected function unfeatureOthers(SponsoredFeature $current): void
    {
        $this->sponsoredFeatures->allFeatured(exceptId: $current->id)
            ->each(fn (SponsoredFeature $other) => $this->sponsoredFeatures->update($other, ['is_featured' => false]));
    }

    protected function uniqueSlug(string $source, ?int $exceptId = null): string
    {
        $slug = Str::slug($source);
        $original = $slug;
        $suffix = 1;

        while ($this->sponsoredFeatures->slugExists($slug, $exceptId)) {
            $slug = "{$original}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
