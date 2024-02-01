<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue;

use App\Application\QueueInterface;
use App\Domain\Vote\Url;
use Redis;

class RedisQueue implements QueueInterface
{
    private const QUEUE_NAME = 'queue:url';

    public function __construct(private readonly Redis $client)
    {
    }

    public function push(Url $url): void
    {
        $this->client->rPush(self::QUEUE_NAME, $url->asString());
    }

    public function pop(): ?Url
    {
        $url = $this->client->lPop(self::QUEUE_NAME);

        return $url ? Url::fromString($url) : null;
    }
}
