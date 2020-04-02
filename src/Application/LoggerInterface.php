<?php

declare(strict_types=1);

namespace App\Application;

interface LoggerInterface
{
    public const DEBUG = 100;
    public const INFO = 200;
    public const NOTICE = 250;
    public const WARNING = 300;
    public const ERROR = 400;
    public const CRITICAL = 500;

    public function log(int $level, string $message, array $context = []): void;
}
