<?php

declare(strict_types=1);

namespace App\Application;

class Rating
{
    private const DEFAULT_AVERAGE_PRECISION = 2;

    private int $count;
    private float $average;

    private function __construct(int $count, float $average)
    {
        $this->count = $count;
        $this->average = $average;
    }

    public static function fromParams(int $count, float $average): self
    {
        return new self($count, $average);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['count'], $data['average']);
    }

    public function toArray(int $averagePrecision = self::DEFAULT_AVERAGE_PRECISION): array
    {
        return [
            'count' => $this->count,
            'average' => round($this->average, $averagePrecision)
        ];
    }
}
