<?php

namespace App\Services;

use App\Enums\NewsletterEditionStatus;
use App\Jobs\DispatchNewsletterEditionSendJob;
use App\Models\NewsletterEdition;
use App\Repositories\Contracts\NewsletterEditionRepositoryInterface;
use Illuminate\Support\Str;

class NewsletterEditionService
{
    public function __construct(protected NewsletterEditionRepositoryInterface $editions) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): NewsletterEdition
    {
        $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $attributes['title']);

        return $this->editions->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(NewsletterEdition $edition, array $attributes): NewsletterEdition
    {
        if (array_key_exists('slug', $attributes) || array_key_exists('title', $attributes)) {
            $attributes['slug'] = $this->uniqueSlug($attributes['slug'] ?? $edition->title, $edition->id);
        }

        return $this->editions->update($edition, $attributes);
    }

    public function delete(NewsletterEdition $edition): bool
    {
        return $this->editions->delete($edition);
    }

    /**
     * Marks the edition Sent immediately (so the admin UI reflects
     * "sending" right away and can't double-fire "Send Now") and
     * dispatches the actual fan-out as a queued job — the HTTP request
     * this runs in returns before a single email has gone out.
     */
    public function send(NewsletterEdition $edition): NewsletterEdition
    {
        if ($edition->isSent()) {
            return $edition;
        }

        $edition = $this->editions->update($edition, [
            'status' => NewsletterEditionStatus::Sent->value,
            'sent_at' => now(),
        ]);

        DispatchNewsletterEditionSendJob::dispatch($edition->id);

        return $edition;
    }

    protected function uniqueSlug(string $source, ?int $exceptId = null): string
    {
        $slug = Str::slug($source);
        $original = $slug;
        $suffix = 1;

        while ($this->editions->slugExists($slug, $exceptId)) {
            $slug = "{$original}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}