<?php

namespace App\Repositories\Contracts;

use App\Models\Magazine;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<Magazine>
 */
interface MagazineRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Magazine;

    public function slugExists(string $slug, ?int $exceptId = null): bool;

    /**
     * The single most recent live issue — what the homepage magazine
     * card shows. Null when nothing has been published yet.
     */
    public function latestPublished(): ?Magazine;

    /**
     * Every live issue, most recent first — an archive/back-issues list
     * can page through this without any new backend work.
     *
     * @return Collection<int, Magazine>
     */
    public function allPublished(int $limit = 12): Collection;

    public function incrementDownloads(Magazine $magazine): void;
}
