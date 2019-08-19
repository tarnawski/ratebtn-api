<?php declare(strict_types=1);

namespace Feedback\Application\Query;

use App\Domain\Vote\Hash;
use App\Domain\VoteRepositoryInterface;

class RatingQueryHandler
{
    /** @var VoteRepositoryInterface */
    private $voteRepository;

    /**
     * @param RatingQuery $query
     * @return Raring
     */
    public function handle(RatingQuery $query): Raring
    {
        $votes = $this->voteRepository->getByHash(Hash::fromString($query->getHash()));

        return new Raring($votes->getNumberOfVotes(), $votes->calculateAverageOfVotes())
    }
}
