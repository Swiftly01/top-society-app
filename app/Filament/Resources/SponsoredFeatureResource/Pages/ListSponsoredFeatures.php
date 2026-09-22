<?php

namespace App\Filament\Resources\SponsoredFeatureResource\Pages;

use App\Filament\Resources\SponsoredFeatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSponsoredFeatures extends ListRecords
{
    protected static string $resource = SponsoredFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
