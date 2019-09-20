<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\Vote\Identity;
use App\Application\Command\CreateVoteCommand;
use App\Application\Command\CreateVoteCommandHandler;
use App\Application\ServiceBus\CommandBus;
use App\Infrastructure\Persistence\InMemoryVoteRepository;
use App\Tests\Integration\Stub\StubCalendar;
use App\Tests\Integration\Stub\StubUuidProvider;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class VoteTest extends TestCase
{
    public function testAddVote(): void
    {
        $voteRepository = new InMemoryVoteRepository();
        $calendar = new StubCalendar(new DateTimeImmutable('2019-06-17 18:24:21'));
        $uuidProvider = new StubUuidProvider('1c46e9ed-d03a-4103-a3f2-2504c1f0052c');

        $voteCommandHandler = new CreateVoteCommandHandler($voteRepository, $uuidProvider, $calendar);

        $commandBus = new CommandBus();
        $commandBus->register($voteCommandHandler);

        $voteCommand = new CreateVoteCommand('http://www.example.com', 3);
        $commandBus->handle($voteCommand);

        $result = $voteRepository->getByIdentity(Identity::fromString('1c46e9ed-d03a-4103-a3f2-2504c1f0052c'));

        $this->assertEquals('1c46e9ed-d03a-4103-a3f2-2504c1f0052c', $result->getIdentity()->asString());
        $this->assertEquals('http://www.example.com', $result->getUrl()->asString());
        $this->assertEquals('2019-06-17 18:24:21', $result->getCreateAt()->format('Y-m-d H:i:s'));
        $this->assertEquals(3, $result->getRate()->asInteger());
    }
}