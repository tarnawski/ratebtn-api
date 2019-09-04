<?php declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Hash
{
    private const MAX_LENGTH = 32;

    /** @var string */
    private $value;

    /**
     * @param string $value
     * @throws InvalidArgumentException
     */
    private function __construct(string $value)
    {
        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException('Hash is longer than 32 characters.');
        }

        if (!ctype_alnum($value)) {
            throw new InvalidArgumentException('Hash contain not alphanumeric characters.');
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return Hash
     * @throws InvalidArgumentException
     */
    public static function fromString(string $value): Hash
    {
        return new Hash($value);
    }

    /**
     * @return string
     */
    public function asString(): string
    {
        return $this->value;
    }
}