<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Exception\SaveVoteException;
use App\Application\LoggerInterface;
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

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param VoteRepositoryInterface $voteRepository
     * @param UuidProviderInterface $uuidProvider
     * @param CalendarInterface $calendar
     * @param LoggerInterface $logger
     */
    public function __construct(
        VoteRepositoryInterface $voteRepository,
        UuidProviderInterface $uuidProvider,
        CalendarInterface $calendar,
        LoggerInterface $logger
    ) {
        $this->voteRepository = $voteRepository;
        $this->uuidProvider = $uuidProvider;
        $this->calendar = $calendar;
        $this->logger = $logger;
    }

    /**
     * @param CreateVoteCommand $command
     * @throws SaveVoteException
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
            $this->logger->log(LoggerInterface::ERROR, $exception->getMessage());
            throw new SaveVoteException('Create vote failed.');
        }

        try {
            $this->voteRepository->persist($vote);
        } catch (PersistenceException $exception) {
            $this->logger->log(LoggerInterface::ERROR, $exception->getMessage());
            throw new SaveVoteException('Save vote failed.');
        }

        $this->logger->log(LoggerInterface::NOTICE, 'Vote was successfully created.', [
            'id' => $vote->getIdentity()->asString(),
            'url' => $vote->getUrl()->asString()
        ]);
    }
}
