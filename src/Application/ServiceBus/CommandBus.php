<?php declare(strict_types=1);

namespace App\Application\ServiceBus;

use ReflectionClass;
use ReflectionException;

class CommandBus
{
    /** @var array */
    private $commandHandlers = [];

    /**
     * @param mixed $commandHandler
     * @throws ReflectionException
     */
    public function register($commandHandler): void
    {
        $className = (new ReflectionClass($commandHandler))->getShortName();
        $this->commandHandlers[$className] = $commandHandler;
    }

    /**
     * @param mixed $command
     * @throws ReflectionException
     */
    public function handle($command): void
    {
        $className = (new ReflectionClass($command))->getShortName();
        $className = sprintf('%sHandler', $className);
        $commandHandler = $this->commandHandlers[$className];

        $commandHandler->handle($command);
    }
}