<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Url
{
    private const MIN_URL_LENGTH = 5;
    private const MAX_URL_LENGTH = 255;

    private string $value;

    private function __construct(string $value)
    {
        if (false === filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf('Url "%s" is not valid.', $value));
        }

        if (self::MIN_URL_LENGTH > strlen($value)) {
            throw new InvalidArgumentException(sprintf('Url "%s" is to short.', $value));
        }

        if (self::MAX_URL_LENGTH < strlen($value)) {
            throw new InvalidArgumentException(sprintf('Url "%s" is to long.', $value));
        }

        $this->value = $value;
    }

    public static function fromString(string $value): Url
    {
        return new Url($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
