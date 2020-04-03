<?php

declare(strict_types=1);

namespace App\Application\Command;

class CreateVoteCommand
{
    private string $url;
    private int $rate;
    private string $fingerprint;

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
