<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Rating;

use App\Domain\Rating\RatingCalculator;
use App\Domain\Vote\Rate;
use App\Domain\Vote\Vote;
use App\Domain\Vote\VoteCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RatingCalculatorTest extends TestCase
{
    /**
     * @dataProvider rateAverageDataProvider
     */
    public function testCalculateAverage(array $votes, float $average): void
    {
        $votes = array_map(fn (int $vote) => $this->createVoteMock($vote), $votes);
        $calculator = new RatingCalculator();

        $this->assertEquals($average, $calculator->calculateAverageOfVotes(new VoteCollection($votes)));
    }

    public function rateAverageDataProvider(): array
    {
        return [
            'vote set 1' => [[], 0.0],
            'vote set 2' => [[2], 2.0],
            'vote set 3' => [[3,5,1], 3.0],
            'vote set 4' => [[2,5,2,2,3], 2.8],
        ];
    }

    /**
     * @dataProvider rateCountDataProvider
     */
    public function testCalculateCount(array $votes, int $count): void
    {
        $votes = array_map(fn (int $vote) => $this->createVoteMock($vote), $votes);
        $calculator = new RatingCalculator();

        $this->assertEquals($count, $calculator->calculateCountOfVotes(new VoteCollection($votes)));
    }

    public function rateCountDataProvider(): array
    {
        return [
            'vote set 1' => [[], 0],
            'vote set 2' => [[4], 1],
            'vote set 3' => [[4,2,1], 3],
            'vote set 4' => [[1,5,4,4,3], 5],
            'vote set 5' => [[2,2,1,3,3,4,2,2,1,1], 10],
        ];
    }

    private function createVoteMock(int $rateValue): MockObject
    {
        $rate = $this->getMockBuilder(Rate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $vote = $this->getMockBuilder(Vote::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rate->method('asInteger')->willReturn($rateValue);
        $vote->method('getRate')->willReturn($rate);

        return $vote;
    }
}
