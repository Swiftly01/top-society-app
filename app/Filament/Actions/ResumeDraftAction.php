<?php

namespace App\Filament\Actions;

use App\Services\DraftService;
use Filament\Actions\Action;

class ResumeDraftAction
{
    // List-page header button that reopens the user's unfinished "create" draft.
    public static function make(string $resource): Action
    {
        return Action::make('resumeDraft')
            ->label('Resume draft')
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->visible(fn () => app(DraftService::class)
                ->find((int) auth()->id(), DraftService::keyFor($resource)) !== null)
            ->url(fn () => $resource::getUrl('create'));
    }
}
