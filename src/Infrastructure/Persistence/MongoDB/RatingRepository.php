<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MongoDB;

use App\Domain\Rating\Rating;
use App\Domain\RatingRepositoryInterface;
use App\Domain\Vote\Url;
use App\Infrastructure\Exception\PersistenceException;
use MongoDB\Database;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Exception\InvalidArgumentException;

class RatingRepository implements RatingRepositoryInterface
{
    private const COLLECTION_NAME = 'rating';

    public function __construct(private readonly Database $database)
    {
    }

    public function getByUrl(Url $url): Rating
    {
        $collection = $this->database->selectCollection(self::COLLECTION_NAME);

        try {
            $rating = (array) $collection->findOne(['url' => $url->asString()]);
        } catch (InvalidArgumentException | RuntimeException $exception) {
            throw new PersistenceException('Failed to save rating.', 0, $exception);
        }

        return new Rating($url, $rating['count'] ?? 0, $rating['average'] ?? 0.0);
    }

    public function update(Rating $rating): void
    {
        $collection = $this->database->selectCollection(self::COLLECTION_NAME);

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
