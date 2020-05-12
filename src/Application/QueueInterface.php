<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Vote\Url;

interface QueueInterface
{
    public function push(Url $url): void;
    public function pop(): ?Url;
}
