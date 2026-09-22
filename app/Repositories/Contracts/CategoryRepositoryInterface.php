<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<Category>
 */
interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Category;

    public function slugExists(string $slug, ?int $exceptId = null): bool;

    /**
     * Top-level categories (no parent), with their children eager-loaded —
     * what a category picker or nav menu builder needs.
     *
     * @return Collection<int, Category>
     */
    public function tree(): Collection;
}
