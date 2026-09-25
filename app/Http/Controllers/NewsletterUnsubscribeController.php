<?php

namespace App\Http\Controllers;

use App\Services\NewsletterSubscriptionService;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterUnsubscribeController extends Controller
{
    public function __construct(protected NewsletterSubscriptionService $subscriptions) {}

    public function show(string $token): Response
    {
        $subscription = $this->subscriptions->unsubscribeByToken($token);

        return Inertia::render('newsletter/status', [
            'status' => $subscription ? 'unsubscribed' : 'invalid',
            'newsletterName' => $subscription?->newsletter?->name,
        ]);
    }
}