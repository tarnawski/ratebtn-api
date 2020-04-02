<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\CalendarInterface;
use DateTimeImmutable;
use DateTimeZone;

class SystemCalendar implements CalendarInterface
{
    private ?DateTimeZone $timeZone;

    public function __construct(DateTimeZone $timeZone = null)
    {
        $this->timeZone = $timeZone;
    }

    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timeZone);
    }
}
