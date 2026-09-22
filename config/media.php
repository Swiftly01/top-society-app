<?php

return [
  'disk' => env('MEDIA_DISK', 'supabase'),

  'max_sizes' => [
    'image' => 10 * 1024,
    'video' => 200 * 1024,
    'attachment' => 50 * 1024
  ],

  'accepted_mime_types' => [
    'image' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'video' => ['video/mp4', 'video/quicktime', 'video/webm'],
    'attachment' => ['application/pdf', 'application/zip', 'audio/mpeg', 'audio/wav'],
  ],

  'root_directory' => env('MEDIA_ROOT_DIRECTORY', 'articles'),

];
