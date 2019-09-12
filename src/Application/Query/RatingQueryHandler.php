<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\RetrieveVotesException;
use App\Domain\Exception\DomainException;
use App\Domain\Vote\Url;
use App\Domain\VoteRepositoryInterface;
use App\Application\DTO\Rating;
use App\Infrastructure\Exception\PersistenceException;

class RatingQueryHandler
{
    /** @var VoteRepositoryInterface */
    private $voteRepository;

    /**
     * @param VoteRepositoryInterface $voteRepository
     */
    public function __construct(VoteRepositoryInterface $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    /**
     * @param RatingQuery $query
     * @return Rating
     * @throws RetrieveVotesException
     */
    public function handle(RatingQuery $query): Rating
    {
        try {
            $url = Url::fromString($query->getUrl());
        } catch (DomainException $exception) {
            throw new RetrieveVotesException('', 0, $exception);
        }

        try {
            $votes = $this->voteRepository->getByUrl($url);
        } catch (PersistenceException $exception) {
            throw new RetrieveVotesException('', 0, $exception);
        }

        return new Rating($votes->getNumberOfVotes(), $votes->calculateAverageOfVotes());
    }
}
