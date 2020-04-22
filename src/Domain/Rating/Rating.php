<?php

declare(strict_types=1);

namespace App\Domain\Rating;

use App\Domain\Vote\Url;

class Rating
{
    private Url $url;
    private int $count;
    private float $average;

    public function __construct(Url $url, int $count, float $average)
    {
        $this->url = $url;
        $this->count = $count;
        $this->average = $average;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getAverage(): float
    {
        return $this->average;
    }
}
