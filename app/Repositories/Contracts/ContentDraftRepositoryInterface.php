<?php

namespace App\Repositories\Contracts;

use App\Models\ContentDraft;
use Carbon\CarbonInterface;

/**
 * @extends RepositoryInterface<ContentDraft>
 */
interface ContentDraftRepositoryInterface extends RepositoryInterface
{
    public function findFor(int $userId, string $key): ?ContentDraft;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function upsertFor(int $userId, string $key, array $payload, string $checksum): ContentDraft;

    public function deleteFor(int $userId, string $key): void;

    public function deleteOlderThan(CarbonInterface $cutoff): int;
}
