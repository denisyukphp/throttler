<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Strategy\InMemoryCounter;
use PHPUnit\Framework\TestCase;

class InMemoryCounterTest extends TestCase
{
    public function testNext(): void
    {
        $inMemoryCounter = new InMemoryCounter(start: 5);

        $this->assertEquals(5, $inMemoryCounter->next('a'));
        $this->assertEquals(5, $inMemoryCounter->next('b'));
        $this->assertEquals(6, $inMemoryCounter->next('a'));
        $this->assertEquals(6, $inMemoryCounter->next('b'));
    }
}
