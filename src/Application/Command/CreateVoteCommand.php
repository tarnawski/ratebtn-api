<?php

declare(strict_types=1);

namespace App\Application\Command;

class CreateVoteCommand
{
    private readonly string $url;
    private readonly int $rate;
    private readonly string $fingerprint;

    public function __construct(string $url, int $rate, string $fingerprint)
    {
        $this->url = $url;
        $this->rate = $rate;
        $this->fingerprint = $fingerprint;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function getFingerprint(): string
    {
        return $this->fingerprint;
    }
}
