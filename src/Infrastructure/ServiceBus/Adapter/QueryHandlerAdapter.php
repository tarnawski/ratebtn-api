<?php declare(strict_types=1);

namespace App\Infrastructure\ServiceBus\Adapter;

class QueryHandlerAdapter
{
    private $handler;

    /**
     * @param $handler
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function __invoke($query)
    {
        return $this->handler->handle($query);
    }
}
