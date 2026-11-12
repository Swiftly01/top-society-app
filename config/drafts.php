<?php

return [
    // Seconds between background autosaves while an editor has a form open.
    'autosave_seconds' => (int) env('DRAFT_AUTOSAVE_SECONDS', 10),

    // Days an untouched draft is kept before the prune command deletes it.
    'retention_days' => (int) env('DRAFT_RETENTION_DAYS', 30),
];
