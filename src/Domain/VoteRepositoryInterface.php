<?php declare(strict_types=1);

namespace App\Domain;

use App\Domain\Vote\Url;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Vote;
use App\Domain\Vote\VoteCollection;
use App\Infrastructure\Exception\PersistenceException;

interface VoteRepositoryInterface
{
    /**
     * @param Identity $identity
     * @return Vote
     * @throws PersistenceException
     */
    public function getByIdentity(Identity $identity): Vote;

    /**
     * @param Url $url
     * @return VoteCollection
     * @throws PersistenceException
     */
    public function getByUrl(Url $url): VoteCollection;

    /**
     * @param Vote $vote
     * @throws PersistenceException
     */
    public function persist(Vote $vote): void;
}
