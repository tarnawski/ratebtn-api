<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Rate
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 1 || $value > 5) {
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
