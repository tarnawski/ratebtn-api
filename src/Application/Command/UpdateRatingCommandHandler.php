<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Exception\ErrorCode;
use App\Application\Exception\RetrieveRatingException;
use App\Application\LoggerInterface;
use App\Application\RatingCacheInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Rating\RatingUpdater;
use App\Domain\Vote\Url;
use App\Infrastructure\Exception\PersistenceException;

class UpdateRatingCommandHandler
{
    public function __construct(
        private readonly RatingUpdater $rateUpdater,
        private readonly RatingCacheInterface $ratingCache,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function handle(UpdateRatingCommand $command): void
    {
        $this->logger->log(LoggerInterface::NOTICE, 'Update rating command appear in system', [
            'url' => $command->getUrl(),
        ]);

        try {
            $url = Url::fromString($command->getUrl());
        } catch (DomainException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'String is not valid url.');
            throw new RetrieveRatingException('String is not valid url.', ErrorCode::DOMAIN_ERROR, $exception);
        }

        try {
            $this->rateUpdater->updateByUrl($url);
        } catch (PersistenceException $exception) {
            $this->logger->log(LoggerInterface::ERROR, 'Rating can not be persist.');
            return;
        }

        if ($this->ratingCache->has($url)) {
            $this->ratingCache->delete($url);
        }

        $this->logger->log(LoggerInterface::NOTICE, 'Rating was successfully updated.', [
            'url' => $command->getUrl(),
        ]);
    }
}
