<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Actions\ResumeDraftAction;
use App\Filament\Resources\BlogPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ResumeDraftAction::make(BlogPostResource::class),
            CreateAction::make(),
        ];
    }
}
