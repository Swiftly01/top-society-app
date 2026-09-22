<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $categories) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Category
    {
        $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $attributes['name']);

        return $this->categories->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Category $category, array $attributes): Category
    {
        if (array_key_exists('slug', $attributes) || array_key_exists('name', $attributes)) {
            $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $category->name, $category->id);
        }

        return $this->categories->update($category, $attributes);
    }

    public function delete(Category $category): bool
    {
        return $this->categories->delete($category);
    }

    protected function uniqueSlug(string $source, ?int $exceptId = null): string
    {
        $slug = Str::slug($source);
        $original = $slug;
        $suffix = 1;

        while ($this->categories->slugExists($slug, $exceptId)) {
            $slug = "{$original}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
