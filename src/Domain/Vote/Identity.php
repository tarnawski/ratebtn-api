<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Identity
{
    private const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    private string $value;

    private function __construct(string $value)
    {
        if (1 !== preg_match(self::UUID_PATTERN, $value)) {
            throw new InvalidArgumentException(sprintf('Identity "%s" is not valid UUID.', $value));
        }

        $this->value = $value;
    }

    public static function fromString(string $value): Identity
    {
        return new Identity($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
