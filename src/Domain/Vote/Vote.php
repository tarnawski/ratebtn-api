<?php declare(strict_types=1);

namespace App\Domain\Vote;

class Vote
{
    /** @var Identity */
    private $identity;

    /** @var Url */
    private $url;

    /** @var Rate */
    private $rate;

    public function __construct(Identity $identity, Url $url, Rate $rate)
    {
        $this->identity = $identity;
        $this->url = $url;
        $this->rate = $rate;
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
}
