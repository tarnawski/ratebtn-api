<?php

declare(strict_types=1);

namespace App\Infrastructure\ServiceBus;

use App\Application\CommandBusInterface;
use App\Infrastructure\ServiceBus\Adapter\CommandHandlerAdapter;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

class SymfonyCommandBus implements CommandBusInterface
{
    private MessageBus $bus;

    public function __construct(array $mapping)
    {
        $mapping = array_map(fn ($handler) => [new CommandHandlerAdapter($handler)], $mapping);

        $this->bus = new MessageBus([new HandleMessageMiddleware(new HandlersLocator($mapping))]);
    }

    public function handle($command): void
    {
        $this->bus->dispatch($command);
    }
}
