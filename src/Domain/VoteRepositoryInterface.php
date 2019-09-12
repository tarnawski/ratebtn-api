<?php declare(strict_types=1);

namespace App\Domain;

use App\Domain\Vote\Url;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Vote;
use App\Domain\Vote\VoteCollection;

interface VoteRepositoryInterface
{
    /**
     * @param Identity $identity
     * @return Vote
     */
    public function getByIdentity(Identity $identity): Vote;

    /**
     * @param Url $url
     * @return VoteCollection
     */
    public function getByUrl(Url $url): VoteCollection;

    /**
     * @param Vote $vote
     */
    public function persist(Vote $vote): void;
}
