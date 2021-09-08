<?php

namespace Orangesoft\Throttler\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Strategy\InMemoryCounter;

class InMemoryCounterTest extends TestCase
{
    public function testIncrement(): void
    {
        $inMemoryCounter = new InMemoryCounter(5);

        $this->assertSame(5, $inMemoryCounter->increment());
    }
}
