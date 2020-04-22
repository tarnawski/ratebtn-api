<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MongoDB;

use MongoDB\Client;
use MongoDB\Database;

class DatabaseFactory
{
    public static function build(string $dns): Database
    {
        return (new Client($dns))->selectDatabase(ltrim(parse_url($dns, PHP_URL_PATH), "/"));
    }
}
