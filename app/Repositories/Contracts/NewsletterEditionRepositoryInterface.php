<?php

namespace App\Repositories\Contracts;

use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<NewsletterEdition>
 */
interface NewsletterEditionRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?NewsletterEdition;

    public function slugExists(string $slug, ?int $exceptId = null): bool;

    /**
     * @return Collection<int, NewsletterEdition>
     */
    public function recentSent(int $limit = 3): Collection;

    public function latestSentFor(int $newsletterId): ?NewsletterEdition;

    public function paginateSentArchive(int $perPage = 15): LengthAwarePaginator;
}