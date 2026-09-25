<?php

namespace App\Repositories\Eloquent;

use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use App\Repositories\Contracts\NewsletterSubscriptionRepositoryInterface;

/**
 * @extends BaseRepository<NewsletterSubscription>
 */
class NewsletterSubscriptionRepository extends BaseRepository implements NewsletterSubscriptionRepositoryInterface
{
    public function __construct(NewsletterSubscription $model)
    {
        parent::__construct($model);
    }

    public function findByToken(string $token): ?NewsletterSubscription
    {
        return $this->query()->where('token', $token)->first();
    }

    public function findByEmailAndNewsletter(string $email, Newsletter $newsletter): ?NewsletterSubscription
    {
        return $this->query()
            ->where('newsletter_id', $newsletter->id)
            ->where('email', $email)
            ->first();
    }

    public function countActiveFor(Newsletter $newsletter): int
    {
        return $this->query()->where('newsletter_id', $newsletter->id)->active()->count();
    }

    public function chunkActiveSubscriberIds(Newsletter $newsletter, int $size, callable $each): void
    {
        $this->model->newQuery()
            ->where('newsletter_id', $newsletter->id)
            ->active()
            ->select('id')
            ->orderBy('id')
            ->chunkById($size, function ($subscriptions) use ($each) {
                $each($subscriptions->pluck('id')->all());
            });
    }
}