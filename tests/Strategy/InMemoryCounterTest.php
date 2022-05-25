<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Strategy\InMemoryCounter;
use PHPUnit\Framework\TestCase;

class InMemoryCounterTest extends TestCase
{
    public function testNext(): void
    {
        $counter = new InMemoryCounter(start: 5);

        $this->assertEquals(5, $counter->next('a'));
        $this->assertEquals(5, $counter->next('b'));
        $this->assertEquals(6, $counter->next('a'));
        $this->assertEquals(6, $counter->next('b'));
    }
}
