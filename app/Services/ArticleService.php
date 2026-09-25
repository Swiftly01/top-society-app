<?php

namespace App\Services;

use App\Enums\ArticleStatus;
use App\Jobs\DispatchArticlePublishedNotificationsJob;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ArticleService
{
    public function __construct(
        protected ArticleRepositoryInterface $articles,
        protected TagRepositoryInterface $tags,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes  May include a 'tags' key: array<int, string> of tag names.
     */
    public function create(array $attributes): Article
    {
        $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $attributes['title']);
        $attributes = $this->applyStatusDefaults($attributes);

        $tagNames = $attributes['tags'] ?? [];
        unset($attributes['tags']);

        return DB::transaction(function () use ($attributes, $tagNames) {
            $article = $this->articles->create($attributes);
            $this->syncTags($article, $tagNames);

            return $article;
        });
    }
    

    

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Article $article, array $attributes): Article
    {
        if (array_key_exists('slug', $attributes) || array_key_exists('title', $attributes)) {
            $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $article->title, $article->id);
        }

        $attributes = $this->applyStatusDefaults($attributes, $article);

        $tagNames = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        return DB::transaction(function () use ($article, $attributes, $tagNames) {
            $article = $this->articles->update($article, $attributes);

            if ($tagNames !== null) {
                $this->syncTags($article, $tagNames);
            }

            return $article;
        });
    }

    public function delete(Article $article): bool
    {
        return $this->articles->delete($article);
    }

    /**
     * Publish immediately. Callers should authorize against the
     * `publish_articles` permission *before* calling this — the service
     * enforces data integrity (a published article always has a
     * published_at), not who's allowed to trigger it.
     */
    public function publish(Article $article): Article
    {
        $wasAlreadyPublished = $article->status === ArticleStatus::Published;

    $article = $this->articles->update($article, [
        'status' => ArticleStatus::Published->value,
        'published_at' => $article->published_at ?? now(),
        'scheduled_for' => null,
    ]);

    // Only notify on the transition into Published, not on every
    // subsequent unrelated edit to an already-published article — this
    // check is what stops "admin fixes a typo" from re-emailing everyone.
    if (! $wasAlreadyPublished) {
        DispatchArticlePublishedNotificationsJob::dispatch($article->id);
    }

    return $article;
    }

    public function unpublish(Article $article): Article
    {
        return $this->articles->update($article, [
            'status' => ArticleStatus::Draft->value,
        ]);
    }

    public function archive(Article $article): Article
    {
        return $this->articles->update($article, [
            'status' => ArticleStatus::Archived->value,
        ]);
    }

    public function submitForReview(Article $article): Article
    {
        return $this->articles->update($article, [
            'status' => ArticleStatus::PendingReview->value,
        ]);
    }

    /**
     * @throws ValidationException if the date isn't in the future
     */
    public function schedule(Article $article, \DateTimeInterface $publishAt): Article
    {
        if ($publishAt <= now()) {
            throw ValidationException::withMessages([
                'scheduled_for' => 'The scheduled publish time must be in the future.',
            ]);
        }

        return $this->articles->update($article, [
            'status' => ArticleStatus::Scheduled->value,
            'scheduled_for' => $publishAt,
        ]);
    }

    /**
     * Flips every article whose scheduled time has arrived to Published.
     * Intended to be called from a scheduled command (see
     * app/Console/Commands and routes/console.php) running every minute.
     *
     * @return int number of articles published
     */
    public function publishDueScheduled(): int
    {
        $due = $this->articles->dueForPublishing();

        foreach ($due as $article) {
            $this->publish($article);
        }

        return $due->count();
    }

    /**
     * @param  array<int, string>  $tagNames
     */
    protected function syncTags(Article $article, array $tagNames): void
    {
        $tags = $this->tags->findOrCreateByNames($tagNames);
        $article->tags()->sync($tags->pluck('id'));
    }

    protected function uniqueSlug(string $source, ?int $exceptId = null): string
    {
        $slug = Str::slug($source);
        $original = $slug;
        $suffix = 1;

        while ($this->articles->slugExists($slug, $exceptId)) {
            $slug = "{$original}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    /**
     * Keeps `status`/`published_at`/`scheduled_for` internally consistent
     * regardless of which combination a form submits — e.g. setting
     * status to Published always stamps published_at if it's missing;
     * setting it to anything else clears scheduled_for.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function applyStatusDefaults(array $attributes, ?Article $existing = null): array
    {
        if (! array_key_exists('status', $attributes)) {
            return $attributes;
        }

        $status = $attributes['status'] instanceof ArticleStatus
            ? $attributes['status']
            : ArticleStatus::from($attributes['status']);

        if ($status === ArticleStatus::Published) {
            $attributes['published_at'] ??= $existing?->published_at ?? now();
            $attributes['scheduled_for'] = null;
        } elseif ($status !== ArticleStatus::Scheduled) {
            $attributes['scheduled_for'] = null;
        }

        return $attributes;
    }
}
