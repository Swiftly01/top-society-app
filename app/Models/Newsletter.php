<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'badge', 'description', 'notify_on_article_publish', 'is_default', 'is_active', 'display_order'])]
class Newsletter extends Model
{
    protected function casts(): array
    {
        return [
            'notify_on_article_publish' => 'boolean',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(NewsletterSubscription::class);
    }

    public function editions(): HasMany
    {
        return $this->hasMany(NewsletterEdition::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeNotifiesOnPublish(Builder $query): Builder
    {
        return $query->where('notify_on_article_publish', true);
    }
}