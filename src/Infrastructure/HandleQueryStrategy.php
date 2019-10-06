<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Prooph\Common\Event\ActionEvent;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\Plugin\AbstractPlugin;

class HandleQueryStrategy extends AbstractPlugin
{
    public function attachToMessageBus(MessageBus $messageBus): void
    {
        $this->listenerHandlers[] = $messageBus->attach(
            MessageBus::EVENT_DISPATCH,
            function (ActionEvent $actionEvent): void {
                if ($actionEvent->getParam(MessageBus::EVENT_PARAM_MESSAGE_HANDLED, false)) {
                    return;
                }

                $message = $actionEvent->getParam(MessageBus::EVENT_PARAM_MESSAGE);
                $handler = $actionEvent->getParam(MessageBus::EVENT_PARAM_MESSAGE_HANDLER);

                $handler->handle($message);
                $actionEvent->setParam(MessageBus::EVENT_PARAM_MESSAGE_HANDLED, true);
            },
            MessageBus::PRIORITY_INVOKE_HANDLER
        );
    }
}
