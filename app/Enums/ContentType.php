<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ContentType: string implements HasLabel
{
    case NewsArticle = 'news_article';
    case BlogPost = 'blog_post';

    public function getLabel(): string
    {
        return match ($this) {
            self::NewsArticle => 'News Article',
            self::BlogPost => 'Blog Post',
        };
    }
}
