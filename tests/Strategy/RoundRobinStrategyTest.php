<?php

namespace Orangesoft\Throttler\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;

class RoundRobinStrategyTest extends TestCase
{
    /**
     * @return InMemoryCounter
     */
    public function testRoundRobin(): InMemoryCounter
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $collection = new Collection($nodes);

        $inMemoryCounter = new InMemoryCounter();

        $strategy = new RoundRobinStrategy($inMemoryCounter);

        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(1, $strategy->getIndex($collection));
        $this->assertSame(2, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));

        return $inMemoryCounter;
    }

    /**
     * @param InMemoryCounter $inMemoryCounter
     *
     * @depends testRoundRobin
     */
    public function testRestart(InMemoryCounter $inMemoryCounter)
    {
        $nodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $collection = new Collection($nodes);

        $strategy = new RoundRobinStrategy($inMemoryCounter);

        $this->assertSame(1, $strategy->getIndex($collection));
        $this->assertSame(2, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
    }
}
