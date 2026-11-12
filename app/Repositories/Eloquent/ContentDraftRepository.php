<?php

namespace App\Repositories\Eloquent;

use App\Models\ContentDraft;
use App\Repositories\Contracts\ContentDraftRepositoryInterface;
use Carbon\CarbonInterface;

/**
 * @extends BaseRepository<ContentDraft>
 */
class ContentDraftRepository extends BaseRepository implements ContentDraftRepositoryInterface
{
    public function __construct(ContentDraft $model)
    {
        parent::__construct($model);
    }

    public function findFor(int $userId, string $key): ?ContentDraft
    {
        return $this->query()->where('user_id', $userId)->where('draft_key', $key)->first();
    }

    // One row per user and form, so repeated autosaves overwrite instead of piling up.
    public function upsertFor(int $userId, string $key, array $payload, string $checksum): ContentDraft
    {
        return $this->query()->updateOrCreate(
            ['user_id' => $userId, 'draft_key' => $key],
            ['payload' => $payload, 'checksum' => $checksum],
        );
    }

    public function deleteFor(int $userId, string $key): void
    {
        $this->query()->where('user_id', $userId)->where('draft_key', $key)->delete();
    }

    public function deleteOlderThan(CarbonInterface $cutoff): int
    {
        return $this->query()->where('updated_at', '<', $cutoff)->delete();
    }
}
