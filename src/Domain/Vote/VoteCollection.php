<?php

declare(strict_types=1);

namespace App\Domain\Vote;

use App\SharedKernel\ItemIteratorTrait;
use Iterator;

class VoteCollection implements Iterator
{
    use ItemIteratorTrait;
}
