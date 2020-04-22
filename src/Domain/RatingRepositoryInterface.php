<?php

namespace App\Domain;

use App\Domain\Rating\Rating;
use App\Domain\Vote\Url;

interface RatingRepositoryInterface
{
    public function getByUrl(Url $url): Rating;

    public function update(Rating $rating): void;
}
