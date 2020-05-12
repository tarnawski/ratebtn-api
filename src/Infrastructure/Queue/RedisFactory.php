<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue;

use Redis;

class RedisFactory
{
    public static function build(string $dns): Redis
    {
        $redis = new Redis();
        $redis->connect(parse_url($dns, PHP_URL_HOST), parse_url($dns, PHP_URL_PORT));

        return $redis;
    }
}
