<?php declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Url
{
    /** @var int */
    private const MIN_URL_LENGTH = 5;

    /** @var int */
    private const MAX_URL_LENGTH = 255;

    /** @var string */
    private $value;

    /**
     * @param string $value
     * @throws InvalidArgumentException
     */
    private function __construct(string $value)
    {
        if (self::MIN_URL_LENGTH > strlen($value)) {
            throw new InvalidArgumentException('Url is to short.');
        }

        if (self::MAX_URL_LENGTH < strlen($value)) {
            throw new InvalidArgumentException('Url is to long.');
        }

        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
