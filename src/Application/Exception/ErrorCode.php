<?php

declare(strict_types=1);

namespace App\Application\Exception;

class ErrorCode
{
    public const DOMAIN_ERROR = 100;
    public const PERSISTENCE_ERROR = 200;
    public const UNKNOWN = 999;
}
