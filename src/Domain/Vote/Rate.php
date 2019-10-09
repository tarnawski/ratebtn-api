<?php declare(strict_types=1);

namespace App\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;

class Rate
{
    /** @var integer */
    private $value;

    /**
     * @param int $value
     * @throws InvalidArgumentException
     */
    private function __construct(int $value)
    {
        if ($value < 1 || $value > 5) {
            throw new InvalidArgumentException(sprintf('Rate "%u" is not valid.', $value));
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
        return new self($value);
    }

    /**
     * @return int
     */
    public function asInteger(): int
    {
        return $this->value;
    }
}
