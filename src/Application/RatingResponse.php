<?php

declare(strict_types=1);

namespace App\Application;

class RatingResponse
{
    private const DEFAULT_AVERAGE_PRECISION = 2;

    private int $count;
    private float $average;

    public function __construct(int $count, float $average)
    {
        $this->count = $count;
        $this->average = $average;
    }

    public function toArray(int $averagePrecision = self::DEFAULT_AVERAGE_PRECISION): array
    {
        return [
            'count' => $this->count,
            'average' => round($this->average, $averagePrecision)
        ];
    }
}
