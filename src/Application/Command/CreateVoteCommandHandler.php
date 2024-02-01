<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Exception\ErrorCode;
use App\Application\Exception\SaveVoteException;
use App\Application\LoggerInterface;
use App\Application\QueueInterface;
use App\Domain\CalendarInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Vote\FraudChecker;
use App\Domain\UuidProviderInterface;
use App\Domain\Vote\Fingerprint;
use App\Infrastructure\Exception\PersistenceException;
use App\Domain\Vote\Url;
use App\Domain\Vote\Identity;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Vote;
use App\Domain\VoteRepositoryInterface;

class CreateVoteCommandHandler
{
    public function __construct(
        private readonly VoteRepositoryInterface $voteRepository,
        private readonly FraudChecker $fraudChecker,
        private readonly UuidProviderInterface $uuidProvider,
        private readonly CalendarInterface $calendar,
        private readonly QueueInterface $queue,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function handle(CreateVoteCommand $command): void
    {
        $this->logger->log(LoggerInterface::NOTICE, 'Create vote command appear in system', [
            'url' => $command->getUrl(),
            'rate' => $command->getRate(),
        ]);

        try {
            $vote = new Vote(
                Identity::fromString($this->uuidProvider->generate()),
                Url::fromString($command->getUrl()),
                Rate::fromInteger($command->getRate()),
                Fingerprint::fromString($command->getFingerprint()),
                $this->calendar->currentTime(),
            );
        } catch (DomainException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'Vote can not be created.');
            throw new SaveVoteException('Vote can not be created.', ErrorCode::DOMAIN_ERROR, $exception);
        }

        if ($this->fraudChecker->check($vote)) {
            $this->logger->log(LoggerInterface::NOTICE, 'Vote classified as fraud.', [
                'url' => $vote->getUrl()->asString(),
                'fingerprint' => $vote->getFingerprint()->asString(),
            ]);

            return;
        }

        try {
            $this->voteRepository->add($vote);
        } catch (PersistenceException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'Vote can not be persist.');
            throw new SaveVoteException('Vote can not be persist.', ErrorCode::PERSISTENCE_ERROR, $exception);
        }

        $this->logger->log(LoggerInterface::NOTICE, 'Vote was successfully created.', [
            'id' => $vote->getIdentity()->asString(),
            'url' => $vote->getUrl()->asString(),
        ]);

        $this->queue->push($vote->getUrl());
    }
}
