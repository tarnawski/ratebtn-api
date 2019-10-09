<?php

namespace App\Application;

class Rating
{
    /** @var integer */
    private $count;

    /** @var float */
    private $average;

    /**
     * @param int $count
     * @param float $average
     */
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
