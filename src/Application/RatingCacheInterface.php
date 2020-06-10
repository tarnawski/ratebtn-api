<?php

namespace App\Application;

use App\Domain\Vote\Url;

interface RatingCacheInterface
{
    public function has(Url $url): bool;
    public function get(Url $url): Rating;
    public function save(Url $url, Rating $rating): void;
    public function delete(Url $url): void;
    public function invalidate(): void;
}
