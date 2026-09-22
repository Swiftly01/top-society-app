<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Enums\ContentType;
use App\Enums\MediaCollection;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $body
 * @property ContentType $type
 * @property ArticleStatus $status
 * @property bool $is_featured
 * @property bool $is_trending
 * @property Carbon|null $published_at
 * @property Carbon|null $scheduled_for
 * @property int|null $category_id
 * @property int|null $author_id
 */
#[Fillable([
    'title',
    'slug',
    'excerpt',
    'body',
    'type',
    'status',
    'is_featured',
    'is_trending',
    'views_count',
    'published_at',
    'scheduled_for',
    'category_id',
    'author_id',
])]
class Article extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'type' => ContentType::class,
            'status' => ArticleStatus::class,
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'views_count' => 'integer',
            'published_at' => 'datetime',
            'scheduled_for' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
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

    public function galleryImages(): MorphMany
    {
        return $this->media()->where('collection', MediaCollection::Gallery->value);
    }

    public function videos(): MorphMany
    {
        return $this->media()->where('collection', MediaCollection::Video->value);
    }

    public function attachments(): MorphMany
    {
        return $this->media()->where('collection', MediaCollection::Attachment->value);
    }

    // --- Query scopes -----------------------------------------------

    public function scopeNewsArticles(Builder $query): Builder
    {
        return $query->where('type', ContentType::NewsArticle->value);
    }

    public function scopeBlogPosts(Builder $query): Builder
    {
        return $query->where('type', ContentType::BlogPost->value);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where('is_trending', true);
    }

    /**
     * Live on the public site: published *and* the publish date has
     * actually arrived (guards against a status flip without a matching
     * `published_at`, and against clock drift on scheduled content).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', ArticleStatus::Published->value)
            ->where('published_at', '<=', now());
    }

    public function scopeInCategory(Builder $query, Category|int $category): Builder
    {
        return $query->where('category_id', $category instanceof Category ? $category->id : $category);
    }


    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where('title', 'like', "%{term}%")->orWhere('excerpt', 'like', '%{excerpt}%');
    }

    // --- Convenience accessors ---------------------------------------

    public function isPublished(): bool
    {
        return $this->status === ArticleStatus::Published
            && $this->published_at !== null
            && $this->published_at->isPast();
    }


    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
