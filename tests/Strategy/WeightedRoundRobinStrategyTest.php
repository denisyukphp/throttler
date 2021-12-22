<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\WeightedRoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class WeightedRoundRobinStrategyTest extends TestCase
{
    private Collection $collection;

    public function setUp(): void
    {
        $this->collection = new Collection([
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ]);
    }

    public function testWeightedRoundRobin(): InMemoryCounter
    {
        $inMemoryCounter = new InMemoryCounter(start: 0);

        $strategy = new WeightedRoundRobinStrategy($inMemoryCounter);

        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));

        return $inMemoryCounter;
    }

    /**
     * @depends testWeightedRoundRobin
     */
    public function testRestartWeightedRoundRobin(InMemoryCounter $inMemoryCounter): void
    {
        $strategy = new WeightedRoundRobinStrategy($inMemoryCounter);

        $this->assertEquals(1, $strategy->getIndex($this->collection));
        $this->assertEquals(2, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
    }
}
