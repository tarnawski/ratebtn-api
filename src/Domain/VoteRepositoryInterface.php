<?php

namespace App\Domain;

use App\Domain\Vote\Url;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Vote;
use App\Domain\Vote\VoteCollection;

interface VoteRepositoryInterface
{
    public function getByIdentity(Identity $identity): Vote;

    public function getByUrl(Url $url): VoteCollection;

    public function persist(Vote $vote): void;
}
