<?php

declare(strict_types=1);

namespace App\Domain\Vote;

class VoteCollection
{
    private array $votes;

    public function __construct(array $votes = [])
    {
        $this->votes = $votes;
    }

    public function getNumberOfVotes(): int
    {
        return count($this->votes);
    }

    public function calculateAverageOfVotes(): float
    {
        if ($this->getNumberOfVotes() === 0) {
            return 0.0;
        }
        $votes = array_map(fn (Vote $vote) => $vote->getRate()->asInteger(), $this->votes);

        return array_sum($votes) / $this->getNumberOfVotes();
    }
}
