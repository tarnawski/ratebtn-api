<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Vote\Vote;

class FraudChecker
{
    private VoteRepositoryInterface $voteRepository;

    public function __construct(VoteRepositoryInterface $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function check(Vote $vote): bool
    {
        foreach ($this->voteRepository->getByUrl($vote->getUrl())->getVotes() as $item) {
            if ($item->getFingerprint()->isEqual($vote->getFingerprint())) {
                return true;
            }
        }

        return false;
    }
}
