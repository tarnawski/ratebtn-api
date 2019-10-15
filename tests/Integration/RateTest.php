<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\Application\RatingResponse;
use App\Application\Query\RatingQuery;
use App\Application\Query\RatingQueryHandler;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Url;
use App\Domain\Vote\Vote;
use App\Domain\Vote\Identity;
use App\Infrastructure\Logger\InMemoryLogger;
use App\Infrastructure\Persistence\InMemoryVoteRepository;
use App\Infrastructure\ServiceBus\SymfonyQueryBus;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RateTest extends TestCase
{
    public function testGetRate(): void
    {
        $voteRepository = new InMemoryVoteRepository([
            new Vote(
                Identity::fromString('1c46e9ed-d03a-4103-a3f2-2504c1f0052c'),
                Url::fromString('http://www.example.com'),
                Rate::fromInteger(3),
                new DateTimeImmutable('2019-06-17 18:24:21')
            ),
            new Vote(
                Identity::fromString('9af43775-cfd3-4276-86dd-d6c30a7511e3'),
                Url::fromString('http://www.example.com'),
                Rate::fromInteger(4),
                new DateTimeImmutable('2019-06-17 18:24:21')
            ),
            new Vote(
                Identity::fromString('25f653ff-6947-40a3-afa4-f4ce13a65e2a'),
                Url::fromString('http://www.site.com'),
                Rate::fromInteger(2),
                new DateTimeImmutable('2019-06-17 18:24:21')
            )
        ]);

        $queryBus = new SymfonyQueryBus([
            RatingQuery::class => new RatingQueryHandler($voteRepository, new InMemoryLogger())
        ]);

        $ratingQuery = new RatingQuery('http://www.example.com');
        $result = $queryBus->handle($ratingQuery);

        $this->assertInstanceOf(RatingResponse::class, $result);
        $this->assertEquals(2, $result->toArray()['count']);
        $this->assertEquals(3.5, $result->toArray()['average']);
    }
}