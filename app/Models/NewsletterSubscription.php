<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['newsletter_id', 'email', 'token', 'subscribed_at', 'verified_at', 'unsubscribed_at'])]
class NewsletterSubscription extends Model
{
    protected static function booted(): void
    {
        static::creating(function (self $subscription) {
            $subscription->token ??= Str::random(48);
            $subscription->subscribed_at ??= now();
        });
    }

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'verified_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

   
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotNull('verified_at')->whereNull('unsubscribed_at');
    }

    
    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('verified_at')->whereNull('unsubscribed_at');
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function isActive(): bool
    {
        return $this->isVerified() && $this->unsubscribed_at === null;
    }
}