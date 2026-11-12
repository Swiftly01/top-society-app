<?php

namespace App\Console\Commands;

use App\Services\DraftService;
use Illuminate\Console\Command;

class PruneStaleDrafts extends Command
{
    protected $signature = 'drafts:prune';

    protected $description = 'Delete autosaved drafts older than the configured retention period';

    public function handle(DraftService $drafts): int
    {
        $this->info("Pruned {$drafts->pruneStale()} stale draft(s).");

        return self::SUCCESS;
    }
}
