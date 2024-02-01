<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Vote\Identity;
use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase
{
    /**
     * @dataProvider validIdentityDataProvider
     */
    public function testCreateIdentityWithValidString(string $value): void
    {
        $identity = Identity::fromString($value);
        $this->assertEquals($value, $identity->asString());
    }

    public function validIdentityDataProvider(): array
    {
        return [
            'valid uuid example 1' => ['83866757-5304-4f11-aa71-f88bd0e4b2a0'],
            'valid uuid example 2' => ['ea8e292f-6452-4194-a5f4-caf2ae71e4cf'],
            'valid uuid example 3' => ['d86b1043-b7d0-4594-b0ae-ac113415674f'],
            'valid uuid example 4' => ['a7c68cc6-d966-43cd-9067-4495bcd37a6a'],
            'valid uuid example 5' => ['3bb57ca2-23e3-4cd7-bede-513c87c8126a'],
        ];
    }

    /**
     * @dataProvider invalidIdentityDataProvider
     */
    public function testCreateIdentityWithInvalidString(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Identity::fromString($value);
    }

    public function invalidIdentityDataProvider(): array
    {
        return [
            'valid uuid version 1' => ['7e111f88-caa4-11e9-a32f-2a2ae2dbcce4'],
            'invalid uuid' => ['e111f8a8-caa4-11e9t34-a32f'],
        ];
    }
}
