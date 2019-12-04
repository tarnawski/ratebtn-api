<?php declare(strict_types=1);

namespace App\Tests\Integration\Fake;

use App\Domain\Vote\Identity;
use App\Domain\Vote\Url;
use App\Domain\Vote\Vote;
use App\Domain\Vote\VoteCollection;
use App\Domain\VoteRepositoryInterface;
use App\Infrastructure\Exception\NotFoundException;

class InMemoryVoteRepository implements VoteRepositoryInterface
{
    /** @var Vote[]  */
    public $votes = [];

    /**
     * @param array $votes
     */
    public function __construct(array $votes = [])
    {
        $this->votes = $votes;
    }

    /**
     * @inheritDoc
     */
    public function getByIdentity(Identity $identity): Vote
    {
        if (!array_key_exists($identity->asString(), $this->votes)) {
            throw new NotFoundException('Vote not found');
        }

        return $this->votes[$identity->asString()];
    }

    /**
     * @inheritDoc
     */
    public function getByUrl(Url $url): VoteCollection
    {
        $votes = array_filter($this->votes, function (Vote $vote) use ($url) {
            return $vote->getUrl()->asString() === $url->asString();
        });

        return new VoteCollection($votes);
    }

    /**
     * @inheritDoc
     */
    public function persist(Vote $vote): void
    {
        $this->votes[$vote->getIdentity()->asString()] = $vote;
    }
}
