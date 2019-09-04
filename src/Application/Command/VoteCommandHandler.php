<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Exception\DomainException;
use App\Infrastructure\Exception\PersistenceException;
use App\Domain\Vote\Hash;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Vote;
use App\Domain\VoteRepositoryInterface;

class VoteCommandHandler
{
    /** @var VoteRepositoryInterface */
    private $voteRepository;

    /**
     * @param VoteRepositoryInterface $voteRepository
     */
    public function __construct(VoteRepositoryInterface $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    /**
     * @param VoteCommand $command
     */
    public function handle(VoteCommand $command): void
    {
        try {
            $vote = new Vote(
                Identity::fromString($command->getIdentity()),
                Hash::fromString($command->getHash()),
                Rate::fromInteger($command->getRate())
            );
        } catch (DomainException $exception) {
            //TODO handle business exception
        }

        try {
            $this->voteRepository->persist($vote);
        } catch (PersistenceException $exception) {
            //TODO handle persistence exception
        }
    }
}
