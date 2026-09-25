<?php

namespace App\Services;

use App\Mail\NewsletterVerificationMail;
use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use App\Repositories\Contracts\NewsletterSubscriptionRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class NewsletterSubscriptionService
{
    public function __construct(protected NewsletterSubscriptionRepositoryInterface $subscriptions) {}

    /**
     * Double opt-in: never marks a subscription active here. It either
     * creates a pending row or, for an existing one that isn't currently
     * active, resets it to pending — either way a fresh verification
     * email goes out and the subscription only becomes real once
     * verify() runs.
     */
    public function subscribe(string $email, Newsletter $newsletter): NewsletterSubscription
    {
        $existing = $this->subscriptions->findByEmailAndNewsletter($email, $newsletter);

        if ($existing?->isActive()) {
            return $existing;
        }

        $subscription = $existing
            ? $this->subscriptions->update($existing, [
                // Rotating the token invalidates any old, unconfirmed
                // verification link still sitting in a previous email —
                // only the newest one should ever work.
                'token' => Str::random(48),
                'verified_at' => null,
                'unsubscribed_at' => null,
                'subscribed_at' => now(),
            ])
            : $this->subscriptions->create([
                'newsletter_id' => $newsletter->id,
                'email' => $email,
            ]);

        $this->sendVerificationEmail($subscription);

        return $subscription;
    }

    /**
     * Idempotent — verifying an already-verified token (someone clicking
     * the link twice) is a no-op, not an error.
     */
    public function verify(string $token): ?NewsletterSubscription
    {
        $subscription = $this->subscriptions->findByToken($token);

        if (! $subscription) {
            return null;
        }

        if (! $subscription->isVerified()) {
            $subscription = $this->subscriptions->update($subscription, ['verified_at' => now()]);
        }

        return $subscription;
    }

    public function unsubscribeByToken(string $token): ?NewsletterSubscription
    {
        $subscription = $this->subscriptions->findByToken($token);

        if (! $subscription) {
            return null;
        }

        return $this->subscriptions->update($subscription, ['unsubscribed_at' => now()]);
    }

    protected function sendVerificationEmail(NewsletterSubscription $subscription): void
    {
        Mail::to($subscription->email)->send(new NewsletterVerificationMail(
            $subscription,
            URL::to("/newsletter/verify/{$subscription->token}"),
        ));
    }
}