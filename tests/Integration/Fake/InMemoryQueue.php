<?php

declare(strict_types=1);

namespace App\Tests\Integration\Fake;

use App\Application\QueueInterface;
use App\Domain\Vote\Url;

class InMemoryQueue implements QueueInterface
{
    private array $list;

    public function __construct(array $list = [])
    {
        $this->list = $list;
    }

    public function push(Url $url): void
    {
        array_push($this->list, $url);
    }

    public function pop(): ?Url
    {
        return array_pop($this->list);
    }

    public function list(): array
    {
        return $this->list;
    }
}