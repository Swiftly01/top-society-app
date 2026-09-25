<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $parent_id
 * @property int $order
 */
#[Fillable(['name', 'slug', 'description', 'parent_id', 'order', 'show_in_primary_nav'])]
class Category extends Model
{  
     protected function casts(): array
    {
        return [
            'order' => 'integer',
            'show_in_primary_nav' => 'boolean',
        ];
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

     public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeInPrimaryNav(Builder $query): Builder
    {
        return $query->where('show_in_primary_nav', true);
    }
}
