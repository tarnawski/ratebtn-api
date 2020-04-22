<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\Vote\FraudChecker;
use App\Domain\Rating\RatingCalculator;
use App\Domain\Rating\RatingUpdater;
use App\Domain\Vote\Identity;
use App\Application\Command\CreateVoteCommand;
use App\Application\Command\CreateVoteCommandHandler;
use App\Infrastructure\Logger\InMemoryLogger;
use App\Infrastructure\ServiceBus\SymfonyCommandBus;
use App\Tests\Integration\Fake\InMemoryRatingRepository;
use App\Tests\Integration\Fake\InMemoryVoteRepository;
use App\Tests\Integration\Stub\StubCalendar;
use App\Tests\Integration\Stub\StubUuidProvider;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class VoteTest extends TestCase
{
    public function testAddVote(): void
    {
        $voteCommandHandler = new CreateVoteCommandHandler(
            $voteRepository = new InMemoryVoteRepository(),
            new FraudChecker($voteRepository),
            new StubUuidProvider('1c46e9ed-d03a-4103-a3f2-2504c1f0052c'),
            new StubCalendar(new DateTimeImmutable('2019-06-17 18:24:21')),
            new RatingUpdater($voteRepository, new InMemoryRatingRepository(), new RatingCalculator()),
            $logger = new InMemoryLogger()
        );
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
