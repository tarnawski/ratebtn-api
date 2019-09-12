<?php declare(strict_types=1);

namespace App\Application\Command;

class VoteCommand
{
    /** @var string */
    private $identity;

    /** @var string */
    private $url;

    /** @var integer */
    private $rate;

    /**
     * @param string $identity
     * @param string $url
     * @param int $rate
     */
    public function __construct(string $identity, string $url, int $rate)
    {
        $this->identity = $identity;
        $this->url = $url;
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getRate(): int
    {
        return $this->rate;
    }
}
