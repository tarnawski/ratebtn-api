<?php

declare(strict_types=1);

namespace App\Domain\Rating;

use App\Domain\RatingRepositoryInterface;
use App\Domain\Vote\Url;
use App\Domain\VoteRepositoryInterface;

class RatingUpdater
{
    private VoteRepositoryInterface $voteRepository;
    private RatingRepositoryInterface $ratingRepository;
    private RatingCalculator $ratingCalculator;

    public function __construct(
        VoteRepositoryInterface $voteRepository,
        RatingRepositoryInterface $ratingRepository,
        RatingCalculator $ratingCalculator
    ) {
        $this->voteRepository = $voteRepository;
        $this->ratingRepository = $ratingRepository;
        $this->ratingCalculator = $ratingCalculator;
    }

    public function updateByUrl(Url $url): void
    {
        $votes = $this->voteRepository->getByUrl($url);
        $this->ratingRepository->update(new Rating(
            $url,
            $this->ratingCalculator->calculateCountOfVotes($votes),
            $this->ratingCalculator->calculateAverageOfVotes($votes)
        ));
    }
}
