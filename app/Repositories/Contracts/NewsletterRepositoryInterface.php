<?php

namespace App\Repositories\Contracts;

use App\Models\Newsletter;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<Newsletter>
 */
interface NewsletterRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Newsletter;

    public function slugExists(string $slug, ?int $exceptId = null): bool;

    /**
     * @return Collection<int, Newsletter>
     */
    public function activeOrdered(): Collection;

    public function default(): ?Newsletter;

    /**
     * @return Collection<int, Newsletter>
     */
    public function notifiesOnPublish(): Collection;
}