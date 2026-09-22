<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Distinguishes the featured image (one per article) from gallery images,
 * video, and general attachments (many per article) without needing
 * separate tables — see App\Models\Media::$collection.
 */
enum MediaCollection: string implements HasLabel
{
    case Featured = 'featured';
    case Gallery = 'gallery';
    case Video = 'video';
    case Attachment = 'attachment';

    public function getLabel(): string
    {
        return match ($this) {
            self::Featured => 'Featured Image',
            self::Gallery => 'Gallery Image',
            self::Video => 'Video',
            self::Attachment => 'Attachment',
        };
    }
}
