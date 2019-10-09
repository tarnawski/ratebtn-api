<?php declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

interface CalendarInterface
{
    public function currentTime() : DateTimeImmutable;
}
