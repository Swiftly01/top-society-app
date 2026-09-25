<?php

namespace App\Mail;

use App\Models\NewsletterSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public NewsletterSubscription $subscription,
        public string $verifyUrl,
    ) {}

    public function build(): self
    {
        return $this->subject("Confirm your subscription to {$this->subscription->newsletter->name}")
            ->view('emails.newsletter-verification')
            ->with([
                'subscription' => $this->subscription,
                'verifyUrl' => $this->verifyUrl,
            ]);
    }
}