<?php

declare(strict_types=1);

namespace App\SharedKernel;

trait ItemIteratorTrait
{
    private array $items = [];
    private int $position;

    public function __construct(array $items, int $position = 0)
    {
        $this->items = $items;
        $this->position = $position;
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position += 1;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}
