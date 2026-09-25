<?php

namespace App\Http\Controllers;

use App\Services\NewsletterSubscriptionService;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterVerifyController extends Controller
{
    public function __construct(protected NewsletterSubscriptionService $subscriptions) {}

    public function show(string $token): Response
    {
        $subscription = $this->subscriptions->verify($token);

        return Inertia::render('newsletter/status', [
            'status' => $subscription ? 'verified' : 'invalid',
            'newsletterName' => $subscription?->newsletter?->name,
        ]);
    }
}