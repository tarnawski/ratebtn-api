<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\UuidProviderInterface;
use Ramsey\Uuid\Uuid;

class RamseyUuidProvider implements UuidProviderInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
