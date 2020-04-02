<?php

declare(strict_types=1);

namespace App\Tests\Integration\Stub;

use App\Domain\CalendarInterface;
use DateTimeImmutable;

class StubCalendar implements CalendarInterface
{
    private DateTimeImmutable $currentTime;

    public function __construct(DateTimeImmutable $currentTime)
    {
        $this->currentTime = $currentTime;
    }

    public function currentTime(): DateTimeImmutable
    {
        return $this->currentTime;
    }

    public function modify(string $modify): void
    {
        $this->currentTime = $this->currentTime->modify($modify);
    }
}
