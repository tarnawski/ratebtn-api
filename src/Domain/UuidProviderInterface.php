<?php declare(strict_types=1);

namespace App\Domain;

interface UuidProviderInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}
