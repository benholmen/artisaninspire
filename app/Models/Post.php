<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Revolution\Bluesky\Facades\Bluesky;

class Post extends Model
{
    protected $fillable = [
        'post',
    ];

    public function postToBluesky(): self
    {
        Bluesky::login(identifier: config('bluesky.identifier'), password: config('bluesky.password'))
            ->post($this->post);

        return $this;
    }
}
