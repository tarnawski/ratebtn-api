<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\VoteRepositoryInterface;

class FraudChecker
{
    public function __construct(private readonly VoteRepositoryInterface $voteRepository)
    {
    }

    public function check(Vote $vote): bool
    {
        foreach ($this->voteRepository->findByUrl($vote->getUrl()) as $item) {
            if ($item->getFingerprint()->isEqual($vote->getFingerprint())) {
                return true;
            }
        }

        return false;
    }
}
