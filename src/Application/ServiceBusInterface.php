<?php declare(strict_types=1);

namespace App\Application;

interface ServiceBusInterface
{
    public function register($handler): void;

    public function handle($command);
}