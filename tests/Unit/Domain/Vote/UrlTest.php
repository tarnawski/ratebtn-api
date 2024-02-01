<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Vote;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Vote\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    /**
     * @dataProvider validUrlDataProvider
     */
    public function testCreateUrlWithValidString(string $value, string $expected): void
    {
        $url = Url::fromString($value);
        $this->assertEquals($expected, $url->asString());
    }

    public static function validUrlDataProvider(): array
    {
        return [
            'valid url schema http' => ['http://www.example.com', 'http://www.example.com'],
            'valid url path' => ['http://www.example.com/help', 'http://www.example.com/help'],
            'valid url query' => ['http://www.example.com?arg=value', 'http://www.example.com?arg=value'],
            'valid url fragment' => ['http://www.example.com#anchor', 'http://www.example.com#anchor'],
        ];
    }

    /**
     * @dataProvider invalidUrlDataProvider
     */
    public function testCreateUrlWithInvalidString(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Url::fromString($value);
    }

    public static function invalidUrlDataProvider(): array
    {
        return [
            'invalid url' => ['www.example'],
            'to short url' => ['go.pl'],
            'to long url' => ['http://chart.apis.google.com/chart?chs=500x500&chma=0,0,100,100&cht=p&chco=FF0000%2' .
                'CFFFF00%7CFF8000%2C00FF00%7C00FF00%2C0000FF&chd=t%3A122%2C42%2C17%2C10%2C8%2C7%2C7%2C7%2C7%2C6%2C' .
                '6%2C6%2C6%2C5%2C5&chl=122%7C42%7C17%7C10%7C8%7C7%7C7%7C7%7C7%7C6%7C6%7C6%7C6%7C5%7C5&chdl=android' .
                '%7Cjava%7Cstack-trace%7Cbroadcastreceiver%7Candroid-ndk%7Cuser-agent%7Candroid-webview%7Cwebview%' .
                '7Cbackground%7Cmultithreading%7Candroid-source%7Csms%7Cadb%7Csollections%7Cactivity|Chart']
        ];
    }
}
