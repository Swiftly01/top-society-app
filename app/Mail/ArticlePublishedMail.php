<?php

namespace App\Mail;

use App\Models\Article;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArticlePublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Article $article,
        public Newsletter $newsletter,
        public string $unsubscribeUrl,
    ) {}

    public function build(): self
    {
        return $this->subject($this->article->title)
            ->view('emails.article-published')
            ->with([
                'article' => $this->article,
                'newsletter' => $this->newsletter,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ]);
    }
}