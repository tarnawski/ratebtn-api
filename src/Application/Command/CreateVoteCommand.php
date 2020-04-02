<?php

declare(strict_types=1);

namespace App\Application\Command;

class CreateVoteCommand
{
    private string $url;
    private int $rate;

    public function __construct(string $url, int $rate)
    {
        $this->url = $url;
        $this->rate = $rate;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getRate(): int
    {
        return $this->rate;
    }
}
