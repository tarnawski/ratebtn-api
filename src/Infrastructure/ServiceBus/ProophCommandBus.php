<?php declare(strict_types=1);

namespace App\Infrastructure\ServiceBus;

use App\Application\ServiceBusInterface;
use Prooph\Common\Event\ActionEvent;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\Plugin\InvokeStrategy\HandleCommandStrategy;

class ProophCommandBus implements ServiceBusInterface
{
    /** @var CommandBus */
    private $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        (new HandleCommandStrategy())->attachToMessageBus($this->commandBus);
    }

    /**
     * @param mixed $commandHandler
     */
    public function register($commandHandler): void
    {
        $this->commandBus->attach(
            MessageBus::EVENT_DISPATCH,
            function (ActionEvent $event) use ($commandHandler): void {
                $event->setParam(MessageBus::EVENT_PARAM_MESSAGE_HANDLER, $commandHandler);
            },
            MessageBus::PRIORITY_ROUTE
        );
    }

    public function handle($command): void
    {
        $this->commandBus->dispatch($command);
    }
}
