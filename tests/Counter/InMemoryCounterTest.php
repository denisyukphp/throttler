<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Counter;

use Orangesoft\Throttler\Counter\InMemoryCounter;
use PHPUnit\Framework\TestCase;

final class InMemoryCounterTest extends TestCase
{
    public function testDefaultInMemoryCounter(): void
    {
        $counter = new InMemoryCounter();

        $expectedResult = range(0, 5);
        $actualResult = [];

        for ($i = 0; $i < 6; ++$i) {
            $actualResult[] = $counter->next();
        }

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testInMemoryCounterWithStartNumber(): void
    {
        $counter = new InMemoryCounter(
            start: 10,
        );

        $expectedResult = range(10, 15);
        $actualResult = [];

        for ($i = 0; $i < 6; ++$i) {
            $actualResult[] = $counter->next();
        }

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testInMemoryCounterWithDifferentNames(): void
    {
        $counter = new InMemoryCounter();

        $expectedResult = [
            0,
            0,
            1,
            1,
            2,
            2,
        ];
        $actualResult = [];

        for ($i = 0; $i < 6; ++$i) {
            $actualResult[] = $counter->next(
                name: 0 === $i % 2 ? 'a' : 'b',
            );
        }

        $this->assertSame($expectedResult, $actualResult);
    }
}
