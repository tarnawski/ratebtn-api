<?php

declare(strict_types=1);

namespace App\Infrastructure\ServiceBus\Adapter;

/**
 * Use to adapt Symfony Messenger component to query handlers
 */
class QueryHandlerAdapter
{
    private $handler;

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function __invoke($query)
    {
        return $this->handler->handle($query);
    }
}
