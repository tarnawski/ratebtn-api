<?php

namespace App\Domain;

use DateTimeImmutable;

interface CalendarInterface
{
    public function currentTime(): DateTimeImmutable;
}
