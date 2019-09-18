<?php declare(strict_types=1);

namespace App\Presentation\Cli\Command;

use App\Application\Command\AddAlertCommand;
use App\Application\Command\VoteCommand;
use App\Application\ServiceBus\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddVoteCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:vote-add';

    /** @var CommandBus */
    private $commandBus;

    /**
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Create new vote.')
            ->setHelp('This command allows you to create new vote...')
            ->addArgument('url', InputArgument::REQUIRED, 'Site url')
            ->addArgument('value', InputArgument::REQUIRED, 'Vote value');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = new VoteCommand(
            Uuid::uuid4()->toString(),
            (string) $input->getArgument('url'),
            (int) $input->getArgument('value')
        );
        $this->commandBus->handle($command);
        $output->writeln('Vote added.');
    }
}