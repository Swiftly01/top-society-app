<?php

namespace App\Jobs;

use App\Models\NewsletterEdition;
use App\Repositories\Contracts\NewsletterSubscriptionRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchNewsletterEditionSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $editionId) {}

    public function handle(NewsletterSubscriptionRepositoryInterface $subscriptions): void
    {
        $edition = NewsletterEdition::with('newsletter')->find($this->editionId);

        if (! $edition) {
            return;
        }

        $subscriptions->chunkActiveSubscriberIds($edition->newsletter, 100, function (array $ids) {
            foreach ($ids as $id) {
                SendNewsletterEditionEmailJob::dispatch($this->editionId, $id);
            }
        });
    }
}