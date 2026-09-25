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
 * An issue of the print/digital magazine that the admin publishes on the
 * homepage: a cover image (reuses the `featured` media collection, like
 * Article/SponsoredFeature) plus the downloadable PDF itself (reuses the
 * `attachment` collection — see config/media.php, which already accepts
 * application/pdf there). Reusing those two collections instead of adding
 * bespoke ones keeps MediaService/HandlesMediaUploads working unchanged.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $issue_label
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $published_at
 * @property int $display_order
 * @property int $downloads_count
 * @property int|null $created_by
 */
#[Fillable([
    'title',
    'slug',
    'issue_label',
    'description',
    'is_active',
    'published_at',
    'display_order',
    'downloads_count',
    'created_by',
])]
class Magazine extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
            'display_order' => 'integer',
            'downloads_count' => 'integer',
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

    public function pdf(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('collection', MediaCollection::Attachment->value);
    }

    public function hasPdf(): bool
    {
        return $this->pdf !== null;
    }

    // --- Query scopes -----------------------------------------------

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Live on the site: active and either published immediately (no date
     * set yet, matching the "leave blank to publish now" admin pattern
     * used elsewhere) or scheduled for a moment already in the past.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function isLive(): bool
    {
        return $this->is_active && ($this->published_at === null || $this->published_at->isPast());
    }
}
