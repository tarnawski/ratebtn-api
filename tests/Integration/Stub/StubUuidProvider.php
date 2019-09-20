<?php declare(strict_types=1);

namespace App\Tests\Integration\Stub;

use App\Domain\UuidProviderInterface;

class StubUuidProvider implements UuidProviderInterface
{
    /** @var string */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function generate(): string
    {
        return $this->uuid;
    }
}