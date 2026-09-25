<?php

namespace App\Repositories\Eloquent;

use App\Models\Newsletter;
use App\Repositories\Contracts\NewsletterRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<Newsletter>
 */
class NewsletterRepository extends BaseRepository implements NewsletterRepositoryInterface
{
    public function __construct(Newsletter $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Newsletter
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

    public function activeOrdered(): Collection
    {
        return $this->query()->active()->orderBy('display_order')->get();
    }

    public function default(): ?Newsletter
    {
        return $this->query()->active()->where('is_default', true)->first()
            ?? $this->query()->active()->orderBy('display_order')->first();
    }

    public function notifiesOnPublish(): Collection
    {
        return $this->query()->active()->notifiesOnPublish()->get();
    }
}