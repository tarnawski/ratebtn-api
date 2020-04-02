<?php

declare(strict_types=1);

namespace App\Infrastructure\Logger;

use App\Application\LoggerInterface;

class NoopLogger implements LoggerInterface
{
    public function log(int $level, string $message, array $context = []): void
    {
        // This logger do nothing.
    }
}
