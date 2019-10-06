<?php declare(strict_types=1);

namespace App\Infrastructure\ServiceBus;

use App\Application\ServiceBusInterface;
use App\Infrastructure\HandleQueryStrategy;
use Prooph\Common\Event\ActionEvent;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\QueryBus;
use ProophTest\ServiceBus\Mock\FetchSomething;
use React\Promise\Deferred;

class ProophQueryBus implements ServiceBusInterface
{
    /** @var QueryBus */
    private $queryBus;

    /**
     * @param QueryBus $queryBus
     */
    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param mixed $queryHandler
     */
    public function register($queryHandler): void
    {
        $this->queryBus->attach(
            MessageBus::EVENT_DISPATCH,
            function (ActionEvent $actionEvent) use (&$receivedMessage, &$dispatchEvent): void {
                $actionEvent->setParam(
                    MessageBus::EVENT_PARAM_MESSAGE_HANDLER,
                    function (FetchSomething $fetchSomething, Deferred $deferred) use (&$receivedMessage): void {
                        var_dump('sf');exit;
                        $deferred->resolve($fetchSomething);
                    }
                );
                $dispatchEvent = $actionEvent;
            },
            MessageBus::PRIORITY_ROUTE
        );
    }

    public function handle($query)
    {
        $promise = $this->queryBus->dispatch($query);

        $promise->then(function ($result) use (&$receivedMessage): void {
            $receivedMessage = $result;
        });    }
}
