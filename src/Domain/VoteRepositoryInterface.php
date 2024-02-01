<?php

namespace App\Domain;

use App\Domain\Vote\Url;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Vote;
use App\Domain\Vote\VoteCollection;

interface VoteRepositoryInterface
{
    public function findByIdentity(Identity $identity): Vote;
    public function findByUrl(Url $url): VoteCollection;
    public function add(Vote $vote): void;
}
