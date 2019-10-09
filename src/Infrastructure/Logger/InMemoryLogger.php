<?php declare(strict_types=1);

namespace App\Infrastructure\Logger;

use App\Application\LoggerInterface;

class InMemoryLogger implements LoggerInterface
{
    /** @var array */
    private $logs;

    /**
     * @param array $logs
     */
    public function __construct(array $logs)
    {
        $this->logs = $logs;
    }

    public function log(int $level, string $message, array $context = []): void
    {
        $this->logs[] = [$level, $message, $context];
    }
}