<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class UniqueUrlHashGenerator
{
    protected $url;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * @throws \Exception
     */
    public function generate()
    {
        do {
            $hash = strtolower(Str::random(6));
        } while ($this->url->where('hash', $hash)->first());

        return $hash;
    }
}
