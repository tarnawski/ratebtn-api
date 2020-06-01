<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Application\Rating as RatingResponse;
use App\Application\Query\RatingQuery;
use App\Application\Query\RatingQueryHandler;
use App\Domain\Rating\Rating;
use App\Domain\Vote\Url;
use App\Infrastructure\Cache\InMemoryCache;
use App\Infrastructure\Logger\InMemoryLogger;
use App\Infrastructure\ServiceBus\SymfonyQueryBus;
use App\Tests\Integration\Fake\InMemoryRatingRepository;
use PHPUnit\Framework\TestCase;

class RateTest extends TestCase
{
    public function testGetRate(): void
    {
        $rateRepository = new InMemoryRatingRepository([
            new Rating(Url::fromString('http://www.example.com'), 2, 3.5),
        ]);
        $cache = new InMemoryCache();
        $logger = new InMemoryLogger();

        $queryBus = new SymfonyQueryBus([
            RatingQuery::class => new RatingQueryHandler($rateRepository, $cache, $logger)
        ]);

        $ratingQuery = new RatingQuery('http://www.example.com');
        $result = $queryBus->handle($ratingQuery);

        $this->assertInstanceOf(RatingResponse::class, $result);
        $this->assertEquals(2, $result->toArray()['count']);
        $this->assertEquals(3.5, $result->toArray()['average']);

        $this->assertCount(1, $logger->getLogs());
    }
}
