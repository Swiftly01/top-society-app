<?php

namespace App\Jobs;

use App\Mail\ArticlePublishedMail;
use App\Models\Article;
use App\Models\NewsletterSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

/**
 * One job per subscriber, not one job for the whole batch — a single
 * bounced/slow mailbox retries in isolation and never blocks or re-sends
 * to everyone else in the batch.
 */
class SendArticlePublishedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $articleId, public int $subscriptionId) {}

    public function handle(): void
    {
        $article = Article::find($this->articleId);
        $subscription = NewsletterSubscription::with('newsletter')->find($this->subscriptionId);

        if (! $article || ! $subscription || ! $subscription->isActive()) {
            return;
        }

        Mail::to($subscription->email)->send(new ArticlePublishedMail(
            $article,
            $subscription->newsletter,
            URL::to("/newsletter/unsubscribe/{$subscription->token}"),
        ));
    }
}