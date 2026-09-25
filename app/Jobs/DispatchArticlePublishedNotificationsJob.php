<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Newsletter;
use App\Repositories\Contracts\NewsletterSubscriptionRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * The lightweight job ArticleService::publish() actually dispatches
 * inline — this one does the (potentially large) subscriber lookup and
 * fans out into per-subscriber jobs, keeping the publish request itself
 * fast regardless of list size.
 */
class DispatchArticlePublishedNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $articleId) {}

    public function handle(NewsletterSubscriptionRepositoryInterface $subscriptions): void
    {
        $article = Article::find($this->articleId);

        if (! $article) {
            return;
        }

        Newsletter::query()->active()->notifiesOnPublish()->each(function (Newsletter $newsletter) use ($subscriptions) {
            $subscriptions->chunkActiveSubscriberIds($newsletter, 100, function (array $ids) {
                foreach ($ids as $id) {
                    SendArticlePublishedNotificationJob::dispatch($this->articleId, $id);
                }
            });
        });
    }
}