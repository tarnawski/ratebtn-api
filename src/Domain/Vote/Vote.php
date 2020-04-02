<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use DateTimeImmutable;

class Vote
{
    private Identity $identity;
    private Url $url;
    private Rate $rate;
    private DateTimeImmutable $createAt;

    public function __construct(Identity $identity, Url $url, Rate $rate, DateTimeImmutable $createAt)
    {
        $this->identity = $identity;
        $this->url = $url;
        $this->rate = $rate;
        $this->createAt = $createAt;
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getRate(): Rate
    {
        return $this->rate;
    }

    public function getCreateAt(): DateTimeImmutable
    {
        return $this->createAt;
    }
}
