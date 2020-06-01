<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use App\Application\Rating;
use App\Application\RatingCacheInterface;
use App\Domain\Vote\Url;
use Psr\Cache\InvalidArgumentException;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\Cache\CacheItem;

class SymfonyRatingCache implements RatingCacheInterface
{
    private const RATING_TAG = 'rating';

    private TagAwareAdapterInterface $cache;

    public function __construct(Redis $client)
    {
        $this->cache = new TagAwareAdapter(
            new RedisAdapter($client),
            new RedisAdapter($client),
        );
    }

    public function has(Url $url): bool
    {
        try {
            $hasItem = $this->cache->hasItem(md5($url->asString()));
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return $hasItem;
    }

    public function get(Url $url): Rating
    {
        try {
            $item = $this->cache->getItem(md5($url->asString()));
        } catch (InvalidArgumentException $exception) {
            throw new CacheException($exception->getMessage());
        }

        return Rating::fromArray(json_decode($item->get(), true));
    }

    public function save(Url $url, Rating $rating): void
    {
        try {
            /** @var CacheItem $item */
            $item = $this->cache->getItem(md5($url->asString()));
            $item->set(json_encode($rating->toArray()));
            $item->tag(self::RATING_TAG);
        } catch (InvalidArgumentException $exception) {
            throw new CacheException($exception->getMessage());
        }

        $this->cache->save($item);
    }

    public function delete(Url $url): void
    {
        try {
            $this->cache->deleteItem(md5($url->asString()));
        } catch (InvalidArgumentException $exception) {
            throw new CacheException($exception->getMessage());
        }
    }

    public function invalidate(): void
    {
        try {
            $this->cache->invalidateTags([self::RATING_TAG]);
        } catch (InvalidArgumentException $exception) {
            throw new CacheException($exception->getMessage());
        }
    }
}
