<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use Iterator;

class VoteCollection implements Iterator
{
    private array $votes;
    private int $position;

    public function __construct(array $votes = [], int $position = 0)
    {
        $this->votes = $votes;
        $this->position = $position;
    }

    public function current(): Vote
    {
        return $this->votes[$this->position];
    }

    public function next(): void
    {
        $this->position ++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->testing[$this->key()]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function reverse(): void
    {
        $this->votes = array_reverse($this->votes);
        $this->rewind();
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
