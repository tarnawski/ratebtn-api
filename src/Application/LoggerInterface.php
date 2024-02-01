<?php

declare(strict_types=1);

namespace App\Application;

interface LoggerInterface
{
    public const DEBUG = 'DEBUG';
    public const INFO = 'INFO';
    public const NOTICE = 'NOTICE';
    public const WARNING = 'WARNING';
    public const ERROR = 'ERROR';
    public const CRITICAL = 'CRITICAL';

    public function log(string $level, string $message, array $context = []): void;
}
