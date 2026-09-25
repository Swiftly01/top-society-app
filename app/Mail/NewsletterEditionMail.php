<?php

namespace App\Mail;

use App\Models\NewsletterEdition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterEditionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public NewsletterEdition $edition,
        public string $unsubscribeUrl,
    ) {}

    public function build(): self
    {
        return $this->subject($this->edition->title)
            ->view('emails.newsletter-edition')
            ->with([
                'edition' => $this->edition,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ]);
    }
}