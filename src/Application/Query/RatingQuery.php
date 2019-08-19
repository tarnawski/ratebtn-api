<?php declare(strict_types=1);

namespace Feedback\Application\Query;

class RatingQuery
{
    /** @var string */
    private $hash;

    /**
     * @param string $hash
     */
    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}