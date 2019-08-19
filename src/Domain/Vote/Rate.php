<?php declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Rate
{
    /** @var integer */
    private $value;

    /**
     * Rate constructor.
     * @param int $value
     * @throws InvalidArgumentException
     */
    private function __construct(int $value)
    {
        if ($value < 1 || $value > 5) {
            throw new InvalidArgumentException('Value should be between 1 and 5!');
        }

        $this->value = $value;
    }

    /**
     * @param int $value
     * @return Rate
     * @throws InvalidArgumentException
     */
    public static function fromInteger(int $value): Rate
    {
        return new Rate($value);
    }

    /**
     * @return int
     */
    public function asInteger(): int
    {
        return $this->value;
    }
}