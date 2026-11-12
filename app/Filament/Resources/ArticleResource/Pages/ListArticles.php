<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Actions\ResumeDraftAction;
use App\Filament\Resources\ArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ResumeDraftAction::make(ArticleResource::class),
            CreateAction::make(),
        ];
    }
}
