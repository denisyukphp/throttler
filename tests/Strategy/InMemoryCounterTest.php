<?php

namespace Orangesoft\Throttler\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Strategy\InMemoryCounter;

class InMemoryCounterTest extends TestCase
{
    public function testIncrement()
    {
        $inMemoryCounter = new InMemoryCounter(5);

        for ($i = 6; $i < 10; $i++) {
            $this->assertSame($i, $inMemoryCounter->increment());
        }
    }
}
