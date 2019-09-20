<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\CalendarInterface;
use App\Domain\Exception\DomainException;
use App\Infrastructure\Exception\PersistenceException;
use App\Domain\Vote\Url;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Vote;
use App\Domain\VoteRepositoryInterface;

class VoteCommandHandler
{
    /** @var VoteRepositoryInterface */
    private $voteRepository;

    /** @var CalendarInterface */
    private $calendar;

    /**
     * @param VoteRepositoryInterface $voteRepository
     * @param CalendarInterface $calendar
     */
    public function __construct(VoteRepositoryInterface $voteRepository, CalendarInterface $calendar)
    {
        $this->voteRepository = $voteRepository;
        $this->calendar = $calendar;
    }

    /**
     * @param VoteCommand $command
     */
    public function handle(VoteCommand $command): void
    {
        try {
            $vote = new Vote(
                Identity::fromString($command->getIdentity()),
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
