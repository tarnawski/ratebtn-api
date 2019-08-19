<?php declare(strict_types=1);

namespace App\Domain\Vote;

class Identity
{
    /** @var string */
    private $value;

    private function __construct(string $value)
    {
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
