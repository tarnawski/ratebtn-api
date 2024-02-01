<?php

declare(strict_types=1);

namespace App\Domain\Rating;

use App\Domain\RatingRepositoryInterface;
use App\Domain\Vote\Url;
use App\Domain\VoteRepositoryInterface;

class RatingUpdater
{
    public function __construct(
        private readonly VoteRepositoryInterface $voteRepository,
        private readonly RatingRepositoryInterface $ratingRepository,
        private readonly RatingCalculator $ratingCalculator,
    ) {
    }

    public function updateByUrl(Url $url): void
    {
        $votes = $this->voteRepository->findByUrl($url);
        $rating = new Rating(
            $url,
            $this->ratingCalculator->calculateCountOfVotes($votes),
            $this->ratingCalculator->calculateAverageOfVotes($votes)
        );
        $this->ratingRepository->update($rating);
    }
}
