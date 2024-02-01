<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Rate
{
    private const MIN_RATE_VALUE = 1;
    private const MAX_RATE_VALUE = 5;

    private readonly int $value;

    private function __construct(int $value)
    {
        if ($value < self::MIN_RATE_VALUE || $value > self::MAX_RATE_VALUE) {
            throw new InvalidArgumentException(sprintf('Rate "%u" is not valid.', $value));
        }

        $this->value = $value;
    }

    public static function fromInteger(int $value): Rate
    {
        return new Rate($value);
    }

    public function asInteger(): int
    {
        return $this->value;
    }
}
