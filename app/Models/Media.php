<?php

namespace App\Models;

use App\Enums\MediaCollection;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $mediable_type
 * @property int $mediable_id
 * @property MediaCollection $collection
 * @property string $disk
 * @property string $path
 * @property string $url
 * @property string $file_name
 * @property string $mime_type
 * @property int $size
 * @property int|null $width
 * @property int|null $height
 * @property string|null $alt_text
 * @property int $order
 */
#[Fillable([
    'mediable_type', 'mediable_id', 'collection', 'disk', 'path', 'url',
    'file_name', 'mime_type', 'size', 'width', 'height', 'alt_text', 'order',
])]
class Media extends Model
{
    protected function casts(): array
    {
        return [
            'collection' => MediaCollection::class,
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'order' => 'integer',
        ];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    /**
     * Human-readable size, e.g. "2.4 MB".
     */
    public function formattedSize(): string
    {
        return static::formatBytes($this->size);
    }

    /**
     * Shared by formattedSize() above and the dashboard's media-storage
     * stat card, so the two never drift into different rounding/units.
     */
    public static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1).' '.$units[$i];
    }
}
