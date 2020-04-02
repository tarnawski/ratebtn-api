<?php

declare(strict_types=1);

namespace App\Infrastructure\ServiceBus\Adapter;

/**
 * Use to adapt Symfony Messenger component to command handlers
 */
class CommandHandlerAdapter
{
    private $handler;

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function __invoke($command)
    {
        return $this->handler->handle($command);
    }
}
