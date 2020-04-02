<?php

declare(strict_types=1);

namespace App\Application;

class RatingResponse
{
    private int $count;
    private float $average;

    public function __construct(int $count, float $average)
    {
        $this->count = $count;
        $this->average = $average;
    }

    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'average' => $this->average
        ];
    }
}
