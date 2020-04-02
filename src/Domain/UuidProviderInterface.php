<?php

namespace App\Domain;

interface UuidProviderInterface
{
    public function generate(): string;
}
