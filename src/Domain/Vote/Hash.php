<?php declare(strict_types=1);

namespace App\Domain\Vote;

class Hash
{
    /** @var string */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $uri): Hash
    {
        return new Hash($uri);
    }

    public function asString(): string
    {
        return $this->value;
    }
}