<?php declare(strict_types=1);

namespace App\Application\Query;

class RatingQuery
{
    /** @var string */
    private $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
