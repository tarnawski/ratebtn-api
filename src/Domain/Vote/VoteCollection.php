<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\SharedKernel\ItemIteratorTrait;
use Iterator;

class VoteCollection implements Iterator
{
    use ItemIteratorTrait;

    public function getNumberOfVotes(): int
    {
        return count($this->items);
    }

    public function calculateAverageOfVotes(): float
    {
        if ($this->getNumberOfVotes() === 0) {
            return 0.0;
        }
        $votes = array_map(fn (Vote $vote) => $vote->getRate()->asInteger(), $this->items);

        return array_sum($votes) / $this->getNumberOfVotes();
    }
}
