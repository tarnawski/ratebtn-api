<?php

declare(strict_types=1);

namespace App\Infrastructure\Logger;

use App\Application\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MonologLogger implements LoggerInterface
{
    private Logger $logger;

    public function __construct(string $name, string $path)
    {
        $this->logger = new Logger($name);
        $this->logger->pushHandler(new StreamHandler($path));
    }

    public function log(int $level, string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
