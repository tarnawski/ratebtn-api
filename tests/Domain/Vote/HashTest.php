<?php

namespace App\Tests\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Vote\Hash;
use PHPUnit\Framework\TestCase;

class HashTest extends TestCase
{
    /**
     * @return array
     */
    public function validHashDataProvider(): array
    {
        return [
            'should accept example value' => ['311328e83b1198734dbe28a7b260da1a'],
            'should accept all numbers' => ['0123456789'],
            'should accept all lowercase letters' => ['abcdefghijklmnopqrstuvwxyz'],
            'should accept all uppercase letters' => ['ABCDEFGHIJKLMNOPQRSTUVWXYZ'],
        ];
    }

    /**
     * @param string $value
     * @throws InvalidArgumentException
     *
     * @dataProvider validHashDataProvider
     */
    public function testCreateHashWithValidString(string $value): void
    {
        $hash = Hash::fromString($value);
        $this->assertEquals($value, $hash->asString());
    }

    /**
     * @return array
     */
    public function invalidHashDataProvider(): array
    {
        return [
            'should not accept spaces in the name' => ['ab cd'],
            'should not accept particular special lowercase chars' => ['àäâæçéèêëìîïóòöôœßùûüÿ'],
            'should not accept particular special uppercase chars' => ['ÀÄÂÆÇÉÈÊËÌÎÏÓÒÖÔŒSSÙÛÜŸ'],
            'should not accept special chars' => ['!@#$%^&*+=[]{}\|;"<>?/()/,.:-'],
            'should not be longer than 32 chars' => ['3IOpS7SxfvnD7OBLtQ6zYArFdDzdL6tEr']

        ];
    }

    /**
     * @param string $value
     * @throws InvalidArgumentException
     *
     * @dataProvider invalidHashDataProvider
     */
    public function testCreateHashWithInvalidString(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Hash::fromString($value);
    }
}