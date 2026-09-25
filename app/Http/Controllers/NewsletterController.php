<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\NewsletterRepositoryInterface;
use App\Services\NewsletterSubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsletterController extends Controller
{
    public function __construct(
        protected NewsletterRepositoryInterface $newsletters,
        protected NewsletterSubscriptionService $subscriptions,
    ) {}

    /**
     * Every subscribe form on the site posts here — DailyBriefing.tsx and
     * SiteFooter.tsx don't ask "which newsletter?", so they omit the
     * `newsletter` field and this falls back to whichever one is flagged
     * `is_default`. NewsletterPlanCard.tsx (on /newsletter) sends an
     * explicit `newsletter` slug for the plan the visitor picked.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'newsletter' => ['nullable', 'string', 'max:255'],
        ]);

        $newsletter = ! empty($validated['newsletter'])
            ? $this->newsletters->findBySlug($validated['newsletter'])
            : $this->newsletters->default();

        if (! $newsletter) {
            Inertia::flash('toast', ['type' => 'error', 'message' => 'Newsletter subscriptions are not available right now.']);

            return back();
        }

        $subscription = $this->subscriptions->subscribe($validated['email'], $newsletter);

        $message = $subscription->isActive()
            ? "You're already subscribed to {$newsletter->name}."
            : "Almost there — check {$validated['email']} to confirm your subscription to {$newsletter->name}.";

        Inertia::flash('toast', ['type' => 'success', 'message' => $message]);

        return back();
    }
}
