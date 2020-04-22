<?php

declare(strict_types=1);

namespace App\Tests\Integration\Fake;

use App\Domain\Rating\Rating;
use App\Domain\RatingRepositoryInterface;
use App\Domain\Vote\Url;

class InMemoryRatingRepository implements RatingRepositoryInterface
{
    public array $ratings = [];

    public function __construct(array $ratings = [])
    {
        $this->ratings = $ratings;
    }

    public function getByUrl(Url $url): Rating
    {
        foreach ($this->ratings as $rating) {
            if ($rating->getUrl()->asString() === $url->asString()) {
                return $rating;
            }
        }

        return new Rating($url, 0, 0.0);
    }

    public function update(Rating $rating): void
    {
        $this->ratings[] = $rating;
    }
}
