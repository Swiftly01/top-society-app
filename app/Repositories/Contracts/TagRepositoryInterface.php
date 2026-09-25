<?php

namespace App\Repositories\Contracts;

use App\Models\Tag;
use Illuminate\Support\Collection;

/**
 * @extends RepositoryInterface<Tag>
 */
interface TagRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Tag;

    public function slugExists(string $slug, ?int $exceptId = null): bool;

    /**
     * Find existing tags by name (case-insensitive) or create any that
     * don't exist yet — what a "type to add a new tag" picker needs.
     *
     * @param  array<int, string>  $names
     * @return Collection<int, Tag>
     */
    public function findOrCreateByNames(array $names): Collection;

    /**
 * @return Collection<int, Tag>
 */
public function suggest(string $term, int $limit = 5): Collection;
}
