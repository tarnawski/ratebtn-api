<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\CalendarInterface;
use App\Domain\Exception\DomainException;
use App\Domain\UuidProviderInterface;
use App\Infrastructure\Exception\PersistenceException;
use App\Domain\Vote\Url;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Vote;
use App\Domain\VoteRepositoryInterface;

class CreateVoteCommandHandler
{
    /** @var VoteRepositoryInterface */
    private $voteRepository;

    /** @var UuidProviderInterface */
    private $uuidProvider;

    /** @var CalendarInterface */
    private $calendar;

    /**
     * @param VoteRepositoryInterface $voteRepository
     * @param UuidProviderInterface $uuidProvider
     * @param CalendarInterface $calendar
     */
    public function __construct(
        VoteRepositoryInterface $voteRepository,
        UuidProviderInterface $uuidProvider,
        CalendarInterface $calendar
    ) {
        $this->voteRepository = $voteRepository;
        $this->uuidProvider = $uuidProvider;
        $this->calendar = $calendar;
    }

    /**
     * @param CreateVoteCommand $command
     */
    public function handle(CreateVoteCommand $command): void
    {
        try {
            $vote = new Vote(
                Identity::fromString($this->uuidProvider->generate()),
                Url::fromString($command->getUrl()),
                Rate::fromInteger($command->getRate()),
                $this->calendar->currentTime()
            );
        } catch (DomainException $exception) {
            return;
            //TODO handle business exception
        }

        try {
            $this->voteRepository->persist($vote);
        } catch (PersistenceException $exception) {
            return;
            //TODO handle persistence exception
        }
    }
}
