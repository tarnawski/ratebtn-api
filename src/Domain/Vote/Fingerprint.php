<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Fingerprint
{
    private const MIN_FINGERPRINT_LENGTH = 5;
    private const MAX_FINGERPRINT_LENGTH = 36;

    private string $value;

    public function __construct(string $value)
    {
        if (self::MIN_FINGERPRINT_LENGTH > strlen($value)) {
            throw new InvalidArgumentException(sprintf('Fingerprint "%s" is to short.', $value));
        }

        if (self::MAX_FINGERPRINT_LENGTH < strlen($value)) {
            throw new InvalidArgumentException(sprintf('Fingerprint "%s" is to long.', $value));
        }

        $this->value = $value;
    }

    public function isEqual(Fingerprint $fingerprint): bool
    {
        return $this->asString() === $fingerprint->asString();
    }

    public static function fromString(string $value): Fingerprint
    {
        return new Fingerprint($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
