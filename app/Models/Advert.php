<?php

namespace App\Models;

use App\Enums\MediaCollection;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * A banner advert shown in the homepage advert slider. The creative is a
 * single image (the `featured` media collection, same as Article /
 * Magazine / TeamMember, so MediaService and HandlesMediaUploads work
 * unchanged) and clicking it sends the reader to `target_url`.
 *
 * @property int $id
 * @property string $title
 * @property string $target_url
 * @property bool $is_active
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property int $display_order
 * @property int $clicks_count
 * @property int|null $created_by
 */
#[Fillable([
    'title',
    'target_url',
    'is_active',
    'starts_at',
    'ends_at',
    'display_order',
    'clicks_count',
    'created_by',
])]
class Advert extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'display_order' => 'integer',
            'clicks_count' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('order');
    }

    public function coverImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('collection', MediaCollection::Featured->value);
    }

    // --- Query scopes -----------------------------------------------

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * On the site right now: switched on, already started (or no start
     * date) and not yet ended (or no end date).
     */
    public function scopeLive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderByDesc('id');
    }

    public function isLive(): bool
    {
        return $this->status() === 'Live';
    }

    /**
     * Human-readable state for the admin table: Inactive, Scheduled,
     * Expired or Live.
     */
    public function status(): string
    {
        return match (true) {
            ! $this->is_active => 'Inactive',
            $this->starts_at !== null && $this->starts_at->isFuture() => 'Scheduled',
            $this->ends_at !== null && $this->ends_at->isPast() => 'Expired',
            default => 'Live',
        };
    }
}
