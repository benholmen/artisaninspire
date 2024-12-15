<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Thunk\Verbs\Support\LilWayneLyrics;

class LilWayne extends LilWayneLyrics
{
    public static function lyrics(): Collection
    {
        return collect(self::LYRICS)
            ->map(fn ($lyric) => "{$lyric} - Lil Wayne");
    }
}
