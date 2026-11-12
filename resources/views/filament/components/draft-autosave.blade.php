{{-- Polls the page's saveDraft() and shows when the last autosave happened. --}}
<div wire:poll.{{ config('drafts.autosave_seconds') }}s="saveDraft" class="text-xs text-gray-500 dark:text-gray-400">
    {{ $getLivewire()->lastDraftSavedAt ? 'Draft autosaved at '.$getLivewire()->lastDraftSavedAt : 'Changes are autosaved as a draft.' }}
</div>
