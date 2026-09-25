<?php

namespace App\Models;

use App\Enums\NewsletterEditionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['newsletter_id', 'title', 'slug', 'excerpt', 'body', 'status', 'sent_at', 'created_by'])]
class NewsletterEdition extends Model
{
    protected function casts(): array
    {
        return [
            'status' => NewsletterEditionStatus::class,
            'sent_at' => 'datetime',
        ];
    }

    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', NewsletterEditionStatus::Sent->value);
    }

    public function isSent(): bool
    {
        return $this->status === NewsletterEditionStatus::Sent;
    }
}