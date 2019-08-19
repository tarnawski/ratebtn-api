<?php declare(strict_types=1);

namespace Feedback\Application\Command;

class VoteCommand
{
    /** @var string */
    private $identity;

    /** @var string */
    private $hash;

    /** @var integer */
    private $rate;

    /**
     * @param string $identity
     * @param string $hash
     * @param int $rate
     */
    public function __construct(string $identity, string $hash, int $rate)
    {
        $this->identity = $identity;
        $this->hash = $hash;
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getRate(): int
    {
        return $this->rate;
    }
}
