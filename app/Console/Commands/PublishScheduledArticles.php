<?php

namespace App\Console\Commands;

use App\Services\ArticleService;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Publish every article whose scheduled publish time has arrived';

    public function handle(ArticleService $articles): int
    {
        $count = $articles->publishDueScheduled();

        $this->info("Published {$count} scheduled article(s).");

        return self::SUCCESS;
    }
}
