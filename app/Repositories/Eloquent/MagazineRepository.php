<?php

namespace App\Repositories\Eloquent;

use App\Models\Magazine;
use App\Repositories\Contracts\MagazineRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<Magazine>
 */
class MagazineRepository extends BaseRepository implements MagazineRepositoryInterface
{
    public function __construct(Magazine $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return parent::query()->with(['coverImage', 'pdf']);
    }

    public function findBySlug(string $slug): ?Magazine
    {
        return $this->query()->where('slug', $slug)->first();
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->when($exceptId, fn (Builder $q) => $q->where('id', '!=', $exceptId))
            ->exists();
    }

    public function latestPublished(): ?Magazine
    {
        return $this->query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('display_order')
            ->first();
    }

    public function allPublished(int $limit = 12): Collection
    {
        return $this->query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('display_order')
            ->limit($limit)
            ->get();
    }

    public function incrementDownloads(Magazine $magazine): void
    {
        $magazine->increment('downloads_count');
    }
}
