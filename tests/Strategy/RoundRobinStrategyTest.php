<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class RoundRobinStrategyTest extends TestCase
{
    private Collection $collection;

    public function setUp(): void
    {
        $this->collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);
    }

    /**
     * @return InMemoryCounter
     */
    public function testRoundRobin(): InMemoryCounter
    {
        $inMemoryCounter = new InMemoryCounter(start: 0);

        $strategy = new RoundRobinStrategy($inMemoryCounter);

        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(1, $strategy->getIndex($this->collection));
        $this->assertEquals(2, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));

        return $inMemoryCounter;
    }

    /**
     * @depends testRoundRobin
     */
    public function testRoundRobinRestart(InMemoryCounter $inMemoryCounter): void
    {
        $strategy = new RoundRobinStrategy($inMemoryCounter);

        $this->assertEquals(1, $strategy->getIndex($this->collection));
        $this->assertEquals(2, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
    }
}
