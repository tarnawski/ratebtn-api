<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\RetrieveRateException;
use App\Application\LoggerInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Vote\Url;
use App\Domain\VoteRepositoryInterface;
use App\Application\Rating;
use App\Infrastructure\Exception\PersistenceException;

class RatingQueryHandler
{
    /** @var VoteRepositoryInterface */
    private $voteRepository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param VoteRepositoryInterface $voteRepository
     * @param LoggerInterface $logger
     */
    public function __construct(VoteRepositoryInterface $voteRepository, LoggerInterface $logger)
    {
        $this->voteRepository = $voteRepository;
        $this->logger = $logger;
    }

    /**
     * @param RatingQuery $query
     * @return Rating
     * @throws RetrieveRateException
     */
    public function handle(RatingQuery $query): Rating
    {
        try {
            $url = Url::fromString($query->getUrl());
        } catch (DomainException $exception) {
            $this->logger->log(LoggerInterface::ERROR, $exception->getMessage());
            throw new RetrieveRateException('String is not valid url.', 0, $exception);
        }

        try {
            $votes = $this->voteRepository->getByUrl($url);
        } catch (PersistenceException $exception) {
            $this->logger->log(LoggerInterface::ERROR, $exception->getMessage());
            throw new RetrieveRateException('Failed to fetch vote by url.', 0, $exception);
        }

        return new Rating($votes->getNumberOfVotes(), $votes->calculateAverageOfVotes());
    }
}
