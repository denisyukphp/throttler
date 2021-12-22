<?php

namespace Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class WeightedRoundRobinStrategyTest extends TestCase
{
    /**
     * @return InMemoryCounter
     */
    public function testWeightedRoundRobin(): InMemoryCounter
    {
        $nodes = [
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ];

        $collection = new Collection($nodes);

        $inMemoryCounter = new InMemoryCounter();

        $strategy = new WeightedRoundRobinStrategy($inMemoryCounter);

        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));

        return $inMemoryCounter;
    }

    /**
     * @param InMemoryCounter $inMemoryCounter
     *
     * @depends testWeightedRoundRobin
     */
    public function testRestart(InMemoryCounter $inMemoryCounter): void
    {
        $nodes = [
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ];

        $collection = new Collection($nodes);

        $strategy = new WeightedRoundRobinStrategy($inMemoryCounter);

        $this->assertSame(1, $strategy->getIndex($collection));
        $this->assertSame(2, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
    }
}
