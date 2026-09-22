<?php

namespace App\Repositories\Contracts;

use App\Models\SponsoredFeature;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<SponsoredFeature>
 */
interface SponsoredFeatureRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?SponsoredFeature;

    public function slugExists(string $slug, ?int $exceptId = null): bool;

    /**
     * The one live, featured placement — what renders as the large
     * image/video tile in the Partnership Dossier. Null if nothing is
     * currently marked featured and active.
     */
    public function activeFeatured(): ?SponsoredFeature;

    /**
     * Live, non-featured placements for the compact list beside the
     * featured tile, ordered by `display_order`.
     *
     * @return Collection<int, SponsoredFeature>
     */
    public function activeSecondary(int $limit = 2): Collection;

    /**
     * Every currently-featured row, most recent first — used when
     * enforcing the single-featured-slot rule (see
     * SponsoredFeatureService::create/update) to find what needs
     * unfeaturing.
     *
     * @return Collection<int, SponsoredFeature>
     */
    public function allFeatured(?int $exceptId = null): Collection;
}
