<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $draft_key
 * @property array<string, mixed> $payload
 * @property string $checksum
 * @property Carbon $updated_at
 */
#[Fillable(['user_id', 'draft_key', 'payload', 'checksum'])]
class ContentDraft extends Model
{
    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    // The editor who owns this draft.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
