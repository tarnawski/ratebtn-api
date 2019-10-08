<?php declare(strict_types=1);

namespace App\Application;

interface CommandBusInterface
{
    /**
     * @param mixed $command
     */
    public function handle($command): void;
}
