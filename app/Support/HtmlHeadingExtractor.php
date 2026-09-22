<?php

namespace App\Support;

use Illuminate\Support\Str;

class HtmlHeadingExtractor
{
    /**
     * @return array{html: string, toc: array<int, array{id: string, label: string}>}
     */
    public static function extract(string $html): array
    {
        if (! str_contains($html, '<h2')) {
            return ['html' => $html, 'toc' => []];
        }

        $toc = [];

        // Matches <h2>Text</h2> or <h2 class="...">Text</h2> (Filament's
        // RichEditor doesn't emit ids on headings by default) and rewrites
        // it to <h2 id="slug">Text</h2>, collecting {id, label} pairs as
        // it goes. A plain preg_replace_callback rather than a full DOM
        // parse — this only ever needs to touch one tag name.
        $rewritten = preg_replace_callback(
            '/<h2(\s[^>]*)?>(.*?)<\/h2>/is',
            function (array $matches) use (&$toc) {
                $label = trim(strip_tags($matches[2]));
                $id = Str::slug($label ?: 'section-'.(count($toc) + 1));

                // Guard against duplicate headings producing duplicate ids.
                $original = $id;
                $suffix = 1;
                while (in_array($id, array_column($toc, 'id'), true)) {
                    $id = "{$original}-{$suffix}";
                    $suffix++;
                }

                $toc[] = ['id' => $id, 'label' => $label];

                return sprintf('<h2 id="%s">%s</h2>', $id, $matches[2]);
            },
            $html,
        );

        return ['html' => $rewritten ?? $html, 'toc' => $toc];
    }
}
