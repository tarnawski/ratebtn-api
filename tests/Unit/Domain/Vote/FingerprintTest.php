<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Vote\Fingerprint;
use App\Domain\Vote\Identity;
use PHPUnit\Framework\TestCase;

class FingerprintTest extends TestCase
{
    /**
     * @dataProvider validFingerprintDataProvider
     */
    public function testCreateFingerprintWithValidString(string $value): void
    {
        $fingerprint = Fingerprint::fromString($value);
        $this->assertEquals($value, $fingerprint->asString());
    }

    public static function validFingerprintDataProvider(): array
    {
        return [
            'valid numerical fingerprint' => ['83866757'],
            'valid alpha numerical fingerprint' => ['af2ae71e4cf'],
        ];
    }

    /**
     * @dataProvider invalidFingerprintDataProvider
     */
    public function testCreateIdentityWithInvalidString(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Fingerprint::fromString($value);
    }

    public static function invalidFingerprintDataProvider(): array
    {
        return [
            'to short fingerprint' => ['7e11'],
            'to long fingerprint' => ['a8caa4a8caa411e9t34e111f8a8caa411e9t34a32f11e9t34e111f8a8caa411e9t34a32f'],
        ];
    }

    public function testComparision(): void
    {
        $this->assertTrue(Fingerprint::fromString('83866757')->isEqual(Fingerprint::fromString('83866757')));
        $this->assertFalse(Fingerprint::fromString('411e9t34e111f8')->isEqual(Fingerprint::fromString('9t34e111f')));
    }
}
