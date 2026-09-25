<?php

namespace App\Repositories\Eloquent;

use App\Models\NewsletterEdition;
use App\Repositories\Contracts\NewsletterEditionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<NewsletterEdition>
 */
class NewsletterEditionRepository extends BaseRepository implements NewsletterEditionRepositoryInterface
{
    public function __construct(NewsletterEdition $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return parent::query()->with('newsletter');
    }

    public function findBySlug(string $slug): ?NewsletterEdition
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

    public function latestSentFor(int $newsletterId): ?NewsletterEdition
    {
        return $this->query()
            ->where('newsletter_id', $newsletterId)
            ->sent()
            ->latest('sent_at')
            ->first();
    }

    public function recentSent(int $limit = 3): Collection
    {
        return $this->query()->sent()->latest('sent_at')->limit($limit)->get();
    }

    public function paginateSentArchive(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->sent()->latest('sent_at')->paginate($perPage);
    }
}