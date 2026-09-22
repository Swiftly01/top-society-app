<?php

namespace App\Filament\Resources;

use App\Enums\ContentType;
use App\Filament\Resources\BlogPostResource\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPostResource\Pages\EditBlogPost;
use App\Filament\Resources\BlogPostResource\Pages\ListBlogPosts;
use BackedEnum;
use UnitEnum;

class BlogPostResource extends BaseArticleResource
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Blog Posts';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $modelLabel = 'Blog Post';

    protected static function contentType(): ContentType
    {
        return ContentType::BlogPost;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'edit' => EditBlogPost::route('/{record}/edit'),
        ];
    }
}
