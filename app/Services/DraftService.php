<?php

namespace App\Services;

use App\Models\ContentDraft;
use App\Repositories\Contracts\ContentDraftRepositoryInterface;

class DraftService
{
    public function __construct(protected ContentDraftRepositoryInterface $drafts) {}

    // Single place that defines how a form maps to a draft key.
    public static function keyFor(string $resource, int|string|null $recordId = null): string
    {
        return $resource.':'.($recordId ?? 'create');
    }

    public function find(int $userId, string $key): ?ContentDraft
    {
        return $this->drafts->findFor($userId, $key);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    // Stores the snapshot, skipping the write when it matches what is already saved.
    public function save(int $userId, string $key, array $payload): ContentDraft
    {
        $checksum = $this->checksum($payload);
        $existing = $this->drafts->findFor($userId, $key);

        if ($existing?->checksum === $checksum) {
            return $existing;
        }

        return $this->drafts->upsertFor($userId, $key, $payload, $checksum);
    }

    public function discard(int $userId, string $key): void
    {
        $this->drafts->deleteFor($userId, $key);
    }

    // Deletes drafts nobody has touched within the retention window.
    public function pruneStale(): int
    {
        return $this->drafts->deleteOlderThan(now()->subDays(config('drafts.retention_days')));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function checksum(array $payload): string
    {
        return sha1(json_encode($payload, JSON_THROW_ON_ERROR));
    }
}
