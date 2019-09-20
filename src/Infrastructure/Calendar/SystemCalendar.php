<?php

declare(strict_types=1);

namespace App\Infrastructure\Calendar;

use App\Domain\CalendarInterface;
use DateTimeImmutable;
use DateTimeZone;

class SystemCalendar implements CalendarInterface
{
    /** @var DateTimeZone */
    private $timeZone;

    public function __construct(DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timeZone);
    }
}
