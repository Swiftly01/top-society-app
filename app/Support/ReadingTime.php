<?php

namespace App\Support;

class ReadingTime
{
    protected const WORDS_PER_MINUTE = 200;

    /**
     * @param  string  $html  Article body HTML (tags stripped before counting).
     */
    public static function estimate(string $html): string
    {
        $wordCount = str_word_count(strip_tags($html));
        $minutes = max(1, (int) ceil($wordCount / self::WORDS_PER_MINUTE));

        return "{$minutes} min read";
    }
}
