<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use App\Application\Rating;
use App\Application\RatingCacheInterface;
use App\Domain\Vote\Url;

class InMemoryCache implements RatingCacheInterface
{
    private array $storage;

    public function __construct(array $storage = [])
    {
        $this->storage = $storage;
    }

    public function has(Url $url): bool
    {
        return array_key_exists($url->asString(), $this->storage);
    }

    public function get(Url $url): Rating
    {
        return $this->storage[$url->asString()];
    }

    public function save(Url $url, Rating $rating): void
    {
        $this->storage[$url->asString()] = $rating;
    }

    public function delete(Url $url): void
    {
        unset($this->storage[$url->asString()]);
    }

    public function invalidate(): void
    {
        $this->storage = [];
    }
}
