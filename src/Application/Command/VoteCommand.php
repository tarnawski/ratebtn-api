<?php declare(strict_types=1);

namespace App\Application\Command;

class VoteCommand
{
    /** @var string */
    private $url;

    /** @var integer */
    private $rate;

    /**
     * @param string $url
     * @param int $rate
     */
    public function __construct(string $url, int $rate)
    {
        $this->url = $url;
        $this->rate = $rate;
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
