<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\FraudChecker;
use App\Domain\Vote\Identity;
use App\Application\Command\CreateVoteCommand;
use App\Application\Command\CreateVoteCommandHandler;
use App\Infrastructure\Logger\InMemoryLogger;
use App\Infrastructure\ServiceBus\SymfonyCommandBus;
use App\Tests\Integration\Fake\InMemoryVoteRepository;
use App\Tests\Integration\Stub\StubCalendar;
use App\Tests\Integration\Stub\StubUuidProvider;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class VoteTest extends TestCase
{
    public function testAddVote(): void
    {
        $voteRepository = new InMemoryVoteRepository();
        $fraudChecker = new FraudChecker($voteRepository);
        $calendar = new StubCalendar(new DateTimeImmutable('2019-06-17 18:24:21'));
        $uuidProvider = new StubUuidProvider('1c46e9ed-d03a-4103-a3f2-2504c1f0052c');
        $logger = new InMemoryLogger();

        $voteCommandHandler = new CreateVoteCommandHandler($voteRepository, $fraudChecker, $uuidProvider, $calendar, $logger);
        $commandBus = new SymfonyCommandBus([
            CreateVoteCommand::class => $voteCommandHandler
        ]);
        $voteCommand = new CreateVoteCommand('http://www.example.com', 3, '1c46e9ed');
        $commandBus->handle($voteCommand);

        $result = $voteRepository->findByIdentity(Identity::fromString('1c46e9ed-d03a-4103-a3f2-2504c1f0052c'));

        $this->assertEquals('1c46e9ed-d03a-4103-a3f2-2504c1f0052c', $result->getIdentity()->asString());
        $this->assertEquals('http://www.example.com', $result->getUrl()->asString());
        $this->assertEquals('2019-06-17 18:24:21', $result->getCreateAt()->format('Y-m-d H:i:s'));
        $this->assertEquals(3, $result->getRate()->asInteger());

        $this->assertCount(2, $logger->getLogs());
    }
}
