<?php

declare(strict_types=1);

namespace App\Presentation\Cli\Command;

use App\Application\Command\CreateVoteCommand;
use App\Application\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:vote-add')]
final class AddVoteCommand extends Command
{
    protected static $defaultName = 'app:vote-add';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create new vote.')
            ->setHelp('This command allows you to create new vote...')
            ->addArgument('url', InputArgument::REQUIRED, 'Site url')
            ->addArgument('value', InputArgument::REQUIRED, 'Vote value')
            ->addArgument('fingerprint', InputArgument::REQUIRED, 'Fingerprint value');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = new CreateVoteCommand(
            (string) $input->getArgument('url'),
            (int) $input->getArgument('value'),
            (string) $input->getArgument('fingerprint'),
        );
        $this->commandBus->handle($command);
        $output->writeln('Vote added.');

        return 0;
    }
}
