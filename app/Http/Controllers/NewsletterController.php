<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class NewsletterController extends Controller
{
    /**
     * Handle a newsletter signup from "The Daily Briefing" or the footer form.
     *
     * This is intentionally minimal — swap the `Log::info` below for a
     * `NewsletterSubscriber::firstOrCreate([...])` (plus a migration) once
     * you're ready to persist subscribers, or dispatch a job to sync with
     * an ESP like Mailchimp/Resend.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        Log::info('Newsletter signup', $validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => "You're subscribed. Welcome to The Daily Briefing."]);

        return back();
    }
}
