<?php

namespace Strategy;

use Orangesoft\Throttler\Strategy\InMemoryCounter;
use PHPUnit\Framework\TestCase;

class InMemoryCounterTest extends TestCase
{
    public function testIncrement(): void
    {
        $inMemoryCounter = new InMemoryCounter(5);

        $this->assertSame(5, $inMemoryCounter->increment());
    }
}
