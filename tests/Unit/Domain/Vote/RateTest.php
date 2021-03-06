<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Vote\Rate;
use PHPUnit\Framework\TestCase;

class RateTest extends TestCase
{
    /**
     * @param int $value
     * @dataProvider validRateDataProvider
     */
    public function testCreateIdentityWithValidInteger(int $value): void
    {
        $rate = Rate::fromInteger($value);
        $this->assertEquals($value, $rate->asInteger());
    }

    public function validRateDataProvider(): array
    {
        return [
            'rate 1' => [1],
            'rate 2' => [2],
            'rate 3' => [3],
            'rate 4' => [4],
            'rate 5' => [5],
        ];
    }

    /**
     * @param int $value
     *
     * @dataProvider invalidIdentityDataProvider
     */
    public function testCreateRateWithInvalidInteger(int $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Rate::fromInteger($value);
    }

    public function invalidIdentityDataProvider(): array
    {
        return [
            'rate equal 0' => [0],
            'negative rate' => [-3],
            'rate higher than 5' => [6]
        ];
    }
}
