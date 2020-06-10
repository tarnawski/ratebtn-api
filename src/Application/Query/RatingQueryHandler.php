<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\ErrorCode;
use App\Application\Exception\RetrieveRatingException;
use App\Application\LoggerInterface;
use App\Application\RatingCacheInterface;
use App\Domain\Exception\DomainException;
use App\Domain\RatingRepositoryInterface;
use App\Domain\Vote\Url;
use App\Application\Rating;
use App\Infrastructure\Exception\PersistenceException;

class RatingQueryHandler
{
    private RatingRepositoryInterface $ratingRepository;
    private RatingCacheInterface $cache;
    private LoggerInterface $logger;

    public function __construct(
        RatingRepositoryInterface $ratingRepository,
        RatingCacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->ratingRepository = $ratingRepository;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function handle(RatingQuery $query): Rating
    {
        $this->logger->log(LoggerInterface::NOTICE, 'Rating query appear in system.', [
            'url' => $query->getUrl(),
        ]);

        try {
            $url = Url::fromString($query->getUrl());
        } catch (DomainException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'String is not valid url.');
            throw new RetrieveRatingException('String is not valid url.', ErrorCode::DOMAIN_ERROR, $exception);
        }

        if ($this->cache->has($url)) {
            return $this->cache->get($url);
        }

        try {
            $rating = $this->ratingRepository->getByUrl($url);
        } catch (PersistenceException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'Votes can not be fetch.');
            throw new RetrieveRatingException('Rating can not be fetch.', ErrorCode::PERSISTENCE_ERROR, $exception);
        }

        $rating = Rating::fromParams($rating->getCount(), $rating->getAverage());
        $this->cache->save($url, $rating);

        return $rating;
    }
}
