<?php declare(strict_types=1);

namespace App\Application;

interface LoggerInterface
{
    /** @var int */
    public const DEBUG = 100;

    /** @var int */
    public const INFO = 200;

    /** @var int */
    public const NOTICE = 250;

    /** @var int */
    public const WARNING = 300;

    /** @var int */
    public const ERROR = 400;

    /** @var int */
    public const CRITICAL = 500;

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     */
    public function log(int $level, string $message, array $context = []): void;
}
