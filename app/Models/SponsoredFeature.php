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
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $body
 * @property string $sponsor_name
 * @property string|null $sponsor_label
 * @property string|null $collaboration_label
 * @property string|null $category_label
 * @property string $cta_label
 * @property string $disclosure_label
 * @property bool $is_featured
 * @property bool $is_active
 * @property int $display_order
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property int|null $created_by
 */
#[Fillable([
    'title',
    'slug',
    'excerpt',
    'body',
    'sponsor_name',
    'sponsor_label',
    'collaboration_label',
    'category_label',
    'cta_label',
    'disclosure_label',
    'is_featured',
    'is_active',
    'display_order',
    'starts_at',
    'ends_at',
    'created_by',
])]
class SponsoredFeature extends Model
{
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
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

    public function featuredImage(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('collection', MediaCollection::Featured->value);
    }

    public function video(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('collection', MediaCollection::Video->value);
    }

    public function hasVideo(): bool
    {
        return $this->video()->exists();
    }

    // --- Query scopes -----------------------------------------------

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSecondary(Builder $query): Builder
    {
        $query = $query->where('is_featured', false);
        // dd([
        //     'sql' => $query->toSql(),
        //     'bindings' => $query->getBindings(),
        //     'count' => $query->count(),
        //     'data' => $query->get(),
        // ]);
        return $query;
    }

    /**
     * Currently live: manually active, and (if set) inside the
     * start/end flighting window.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(fn(Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn(Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    public function isLive(): bool
    {
        return $this->is_active
            && ($this->starts_at === null || $this->starts_at->isPast())
            && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function displaySponsorLabel(): string
    {
        return $this->sponsor_label ?: "Sponsored by {$this->sponsor_name}";
    }
}
