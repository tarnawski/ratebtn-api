<?php

declare(strict_types=1);

namespace App\Application;

interface CommandBusInterface
{
    public function handle($command): void;
}
