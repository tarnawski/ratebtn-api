<?php

declare(strict_types=1);

namespace App\Domain\Rating;

use App\Domain\Vote\Vote;
use App\Domain\Vote\VoteCollection;

class RatingCalculator
{
    public function calculateCountOfVotes(VoteCollection $votes): int
    {
        return count(iterator_to_array($votes));
    }

    public function calculateAverageOfVotes(VoteCollection $votes): float
    {
        if ($this->calculateCountOfVotes($votes) === 0) {
            return 0.0;
        }
        $rates = array_map(fn (Vote $vote) => $vote->getRate()->asInteger(), iterator_to_array($votes));

        return array_sum($rates) / $this->calculateCountOfVotes($votes);
    }
}
