<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\LoggerInterface;
use App\Domain\Rating\RatingUpdater;
use App\Domain\Vote\Url;
use App\Infrastructure\Exception\PersistenceException;

class UpdateRatingCommandHandler
{
    private RatingUpdater $rateUpdater;
    private LoggerInterface $logger;

    public function __construct(RatingUpdater $rateUpdater, LoggerInterface $logger)
    {
        $this->rateUpdater = $rateUpdater;
        $this->logger = $logger;
    }

    public function handle(UpdateRatingCommand $command): void
    {
        $this->logger->log(LoggerInterface::NOTICE, 'Update rating command appear in system', [
            'url' => $command->getUrl(),
        ]);

        try {
            $this->rateUpdater->updateByUrl(Url::fromString($command->getUrl()));
        } catch (PersistenceException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'Rating can not be persist.');
            return;
        }

        $this->logger->log(LoggerInterface::NOTICE, 'Rating was successfully updated.', [
            'url' => $command->getUrl(),
        ]);
    }
}
