<?php declare(strict_types=1);

namespace App\Domain\Vote;

class Vote
{
    /** @var Identity */
    private $identity;

    /** @var Hash */
    private $hash;

    /** @var Rate */
    private $rate;

    public function __construct(Identity $identity, Hash $hash, Rate $rate)
    {
        $this->identity = $identity;
        $this->hash = $hash;
        $this->rate = $rate;
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    public function getHash(): Hash
    {
        return $this->hash;
    }

    public function getRate(): Rate
    {
        return $this->rate;
    }
}
