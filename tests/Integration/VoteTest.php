<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\Application\Command\VoteCommand;
use App\Application\Command\VoteCommandHandler;
use App\Application\ServiceBus\CommandBus;
use App\Infrastructure\Presistance\InMemoryVoteRepository;
use PHPUnit\Framework\TestCase;

class VoteTest extends TestCase
{
    public function testAddVote(): void
    {
        $voteRepository = new InMemoryVoteRepository();
        $voteCommandHandler = new VoteCommandHandler($voteRepository);

        $commandBus = new CommandBus();
        $commandBus->register($voteCommandHandler);

        $voteCommand = new VoteCommand('1c46e9ed-d03a-4103-a3f2-2504c1f0052c', 'http://www.example.com', 3);
        $commandBus->handle($voteCommand);

        $this->assertCount(1, $voteRepository->votes);
    }
}