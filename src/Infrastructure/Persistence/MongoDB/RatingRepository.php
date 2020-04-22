<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MongoDB;

use App\Domain\Rating\Rating;
use App\Domain\RatingRepositoryInterface;
use App\Domain\Vote\Url;
use App\Infrastructure\Exception\PersistenceException;
use MongoDB\Client;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Exception\InvalidArgumentException;

class RatingRepository implements RatingRepositoryInterface
{
    private const DATABASE_NAME = 'ratebtn';
    private const COLLECTION_NAME = 'rating';

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getByUrl(Url $url): Rating
    {
        $collection = $this->client->selectCollection(self::DATABASE_NAME, self::COLLECTION_NAME);

        try {
            $rating = (array) $collection->findOne(['url' => $url->asString()]);
        } catch (InvalidArgumentException | RuntimeException $exception) {
            throw new PersistenceException('Failed to save rating.', 0, $exception);
        }

        return new Rating($url, $rating['count'] ?? 0, $rating['average'] ?? 0.0);
    }

    public function update(Rating $rating): void
    {
        $collection = $this->client->selectCollection(self::DATABASE_NAME, self::COLLECTION_NAME);

        try {
            $collection->updateOne(
                ['url' => $rating->getUrl()->asString()],
                ['$set' => [
                    'count' => $rating->getCount(),
                    'average' => $rating->getAverage(),
                ]],
                ['upsert' => true]
            );
        } catch (InvalidArgumentException | RuntimeException $exception) {
            throw new PersistenceException('Failed to save rating.', 0, $exception);
        }
    }
}
