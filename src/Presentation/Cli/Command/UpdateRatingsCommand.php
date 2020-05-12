<?php

declare(strict_types=1);

namespace App\Presentation\Cli\Command;

use App\Application\Command\UpdateRatingCommand;
use App\Application\CommandBusInterface;
use App\Application\QueueInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateRatingsCommand extends Command
{
    protected static $defaultName = 'app:update-ratings';

    private CommandBusInterface $commandBus;
    private QueueInterface $queue;

    public function __construct(CommandBusInterface $commandBus, QueueInterface $queue)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->queue = $queue;
    }

    protected function configure()
    {
        $this
            ->setDescription('Update ratings')
            ->setHelp('This command allows you to update ratings...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while ($url = $this->queue->pop()) {
            $command = new UpdateRatingCommand($url->asString());
            $this->commandBus->handle($command);
        }

        return 0;
    }
}
