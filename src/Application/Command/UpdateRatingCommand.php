<?php

declare(strict_types=1);

namespace App\Application\Command;

class UpdateRatingCommand
{
    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
