<?php

namespace App\Jobs;

use App\Mail\NewsletterEditionMail;
use App\Models\NewsletterEdition;
use App\Models\NewsletterSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendNewsletterEditionEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $editionId, public int $subscriptionId) {}

    public function handle(): void
    {
        $edition = NewsletterEdition::with('newsletter')->find($this->editionId);
        $subscription = NewsletterSubscription::find($this->subscriptionId);

        if (! $edition || ! $subscription || ! $subscription->isActive()) {
            return;
        }

        Mail::to($subscription->email)->send(new NewsletterEditionMail(
            $edition,
            URL::to("/newsletter/unsubscribe/{$subscription->token}"),
        ));
    }
}