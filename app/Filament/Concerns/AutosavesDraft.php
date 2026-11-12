<?php

namespace App\Filament\Concerns;

use App\Models\ContentDraft;
use App\Services\DraftService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Throwable;

/**
 * Add to any Filament Create/Edit page and drop the draft-autosave view into its form.
 * Fields ending in "_temp" (file uploads) are never snapshotted because they cannot be restored.
 */
trait AutosavesDraft
{
    // Hash of the form as first loaded, so only real edits create a draft.
    public string $draftBaselineHash = '';

    // Key captured on load, because a created record changes the page's own key.
    public string $draftKey = '';

    public bool $hasDraft = false;

    // Time of the last successful autosave, shown by the indicator view.
    public ?string $lastDraftSavedAt = null;

    // Extra fields a page wants kept out of its drafts.
    protected function draftExcludedFields(): array
    {
        return [];
    }

    // Filament hook: runs right after the form is filled, so we restore here.
    protected function afterFill(): void
    {
        $this->draftKey = DraftService::keyFor(static::getResource(), $this->record?->getKey());
        $this->draftBaselineHash = $this->drafts()->checksum($this->draftSnapshot());

        $draft = $this->drafts()->find($this->draftUserId(), $this->draftKey);

        if (! $draft) {
            return;
        }

        if ($this->isDraftStale($draft)) {
            $this->drafts()->discard($this->draftUserId(), $this->draftKey);

            return;
        }

        $this->restoreDraft($draft);
    }

    // Polled by the indicator view; stores the form only when it differs from the baseline.
    public function saveDraft(): void
    {
        $snapshot = $this->draftSnapshot();

        if ($this->drafts()->checksum($snapshot) === $this->draftBaselineHash) {
            $this->hasDraft && $this->clearDraft();
            $this->skipRender();

            return;
        }

        try {
            $draft = $this->drafts()->save($this->draftUserId(), $this->draftKey, $snapshot);

            $this->hasDraft = true;
            $this->lastDraftSavedAt = $draft->updated_at->format('H:i:s');
        } catch (Throwable $e) {
            report($e);
            $this->skipRender();
        }
    }

    // Wired to the "Discard draft" notification button.
    #[On('discard-draft')]
    public function discardDraft(): void
    {
        $this->clearDraft();
        $this->fillForm();
    }

    // Call after the record is really saved so the draft doesn't resurface.
    protected function discardSavedDraft(): void
    {
        $this->clearDraft();
    }

    protected function clearDraft(): void
    {
        $this->drafts()->discard($this->draftUserId(), $this->draftKey);

        $this->hasDraft = false;
        $this->lastDraftSavedAt = null;
    }

    // Overlays the saved values on the freshly loaded form and tells the editor.
    protected function restoreDraft(ContentDraft $draft): void
    {
        $this->form->fill([...$this->form->getRawState(), ...$draft->payload]);

        $this->hasDraft = true;
        $this->lastDraftSavedAt = $draft->updated_at->format('H:i:s');

        Notification::make()
            ->title('Unsaved draft restored')
            ->body('Last autosaved '.$draft->updated_at->diffForHumans().'.')
            ->warning()
            ->persistent()
            ->actions([
                Action::make('discard')->label('Discard draft')->color('danger')->button()->dispatch('discard-draft')->close(),
            ])
            ->send();
    }

    // A draft older than the record's last update would overwrite newer work.
    protected function isDraftStale(ContentDraft $draft): bool
    {
        return $this->record !== null && $draft->updated_at->lt($this->record->updated_at);
    }

    // Current form values minus anything that cannot be stored as JSON.
    protected function draftSnapshot(): array
    {
        return collect($this->form->getRawState())
            ->reject(fn ($value, $field) => str_ends_with((string) $field, '_temp')
                || in_array($field, $this->draftExcludedFields(), true))
            ->all();
    }

    protected function draftUserId(): int
    {
        return (int) auth()->id();
    }

    protected function drafts(): DraftService
    {
        return app(DraftService::class);
    }
}
