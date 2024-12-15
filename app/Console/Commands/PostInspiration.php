<?php

namespace App\Console\Commands;

use \Exception;
use App\Models\Post;
use App\Support\LilWayne;

use function Laravel\Prompts\info;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class PostInspiration extends Command
{
    protected $signature = 'bluesky:post-inspiration {--dry-run}';
    protected $description = 'Pipe artisan:inspire to Bluesky';

    public function handle()
    {
        do {
            $inspiration = $this->inspiration();
        } while (Post::wherePost($inspiration)->exists());

        if (str($inspiration)->length() > 300) {
            throw new Exception("Quote is too long to post on Bluesky:\n{$inspiration}");
        }

        info($inspiration);

        if ($this->option('dry-run')) {
            info('(did not post)');

            return;
        }

        Post::create(['post' => $inspiration])
            ->postToBluesky();

        info('https://bsky.app/profile/' . config('bluesky.identifier'));
    }

    private function inspiration(): string
    {
        $quote = $this->randomQuote();

        [$text, $author] = str($quote)->explode('-');

        return sprintf(
            "“%s”\n  — %s",
            trim($text),
            trim($author),
        );
    }

    private function randomQuote(): string
    {
        $quotes = Inspiring::quotes()
            ->merge(LilWayne::lyrics())
            ->merge([
                "Try to be a rainbow in someone's cloud. - Maya Angelou",
                "You are never too old to set another goal or to dream a new dream. - C.S. Louis",
                "Concentrate all your thoughts upon the work in hand. The Sun's rays do not burn until brought to a focus. - Alexander Graham Bell",
                "It takes as much energy to wish as it does to plan. - Eleanor Roosevelt",
                "You are imperfect, you are wired for struggle, but you are worthy of love and belonging. - Brené Brown",
                "If you see someone without a smile, give 'em yours! - Dolly Parton",
                "I've been absolutely terrified every moment of my life - and I've never let it keep me from doing a single thing I wanted to do. - Georgia O'Keeffe",
            ]);

        return $quotes->random();
    }
}
