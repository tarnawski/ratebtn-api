<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Application\RatingResponse;
use App\Application\Query\RatingQuery;
use App\Application\Query\RatingQueryHandler;
use App\Domain\Rating\Rating;
use App\Domain\Vote\Fingerprint;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Url;
use App\Domain\Vote\Vote;
use App\Domain\Vote\Identity;
use App\Infrastructure\Logger\InMemoryLogger;
use App\Infrastructure\ServiceBus\SymfonyQueryBus;
use App\Tests\Integration\Fake\InMemoryRatingRepository;
use App\Tests\Integration\Fake\InMemoryVoteRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RateTest extends TestCase
{
    public function testGetRate(): void
    {
        $rateRepository = new InMemoryRatingRepository([
            new Rating(Url::fromString('http://www.example.com'), 2, 3.5),
        ]);
        $logger = new InMemoryLogger();

        $queryBus = new SymfonyQueryBus([
            RatingQuery::class => new RatingQueryHandler($rateRepository, $logger)
        ]);

        $ratingQuery = new RatingQuery('http://www.example.com');
        $result = $queryBus->handle($ratingQuery);

        $this->assertInstanceOf(RatingResponse::class, $result);
        $this->assertEquals(2, $result->toArray()['count']);
        $this->assertEquals(3.5, $result->toArray()['average']);

        $this->assertCount(1, $logger->getLogs());
    }
}
