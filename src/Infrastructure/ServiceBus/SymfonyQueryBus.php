<?php declare(strict_types=1);

namespace App\Infrastructure\ServiceBus;

use App\Application\QueryBusInterface;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonyQueryBus implements QueryBusInterface
{
    /** @var MessageBus */
    private $bus;

    public function __construct($mapping)
    {
        $this->bus = new MessageBus([new HandleMessageMiddleware(new HandlersLocator($mapping))]);
    }

    /**
     * @param mixed $query
     * @return mixed
     */
    public function handle($query)
    {
        return $this->bus->dispatch($query)->last(HandledStamp::class)->getResult();
    }
}