<?php

namespace App\Repositories\Contracts;

use App\Models\Newsletter;
use App\Models\NewsletterSubscription;

/**
 * @extends RepositoryInterface<NewsletterSubscription>
 */
interface NewsletterSubscriptionRepositoryInterface extends RepositoryInterface
{
    public function findByToken(string $token): ?NewsletterSubscription;

    public function findByEmailAndNewsletter(string $email, Newsletter $newsletter): ?NewsletterSubscription;

    public function countActiveFor(Newsletter $newsletter): int;

    /**
     * Streams active subscriber IDs for a newsletter in fixed-size
     * batches without ever loading the whole list into memory — the
     * actual scalability mechanism for sending to large lists. `$each`
     * receives a batch (array of ids), not one at a time, so callers can
     * dispatch one queued job per id per batch.
     */
    public function chunkActiveSubscriberIds(Newsletter $newsletter, int $size, callable $each): void;
}