<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\ErrorCode;
use App\Application\Exception\RetrieveRateException;
use App\Application\LoggerInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Vote\Url;
use App\Domain\VoteRepositoryInterface;
use App\Application\RatingResponse;
use App\Infrastructure\Exception\PersistenceException;

class RatingQueryHandler
{
    private VoteRepositoryInterface $voteRepository;
    private LoggerInterface $logger;

    public function __construct(VoteRepositoryInterface $voteRepository, LoggerInterface $logger)
    {
        $this->voteRepository = $voteRepository;
        $this->logger = $logger;
    }

    public function handle(RatingQuery $query): RatingResponse
    {
        $this->logger->log(LoggerInterface::NOTICE, 'Rating query appear in system.', [
            'url' => $query->getUrl(),
        ]);

        try {
            $url = Url::fromString($query->getUrl());
        } catch (DomainException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'String is not valid url.');
            throw new RetrieveRateException('String is not valid url.', ErrorCode::DOMAIN_ERROR, $exception);
        }

        try {
            $votes = $this->voteRepository->getByUrl($url);
        } catch (PersistenceException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'Votes can not be fetch.');
            throw new RetrieveRateException('Votes can not be fetch.', ErrorCode::PERSISTENCE_ERROR, $exception);
        }

        return new RatingResponse($votes->getNumberOfVotes(), $votes->calculateAverageOfVotes());
    }
}
