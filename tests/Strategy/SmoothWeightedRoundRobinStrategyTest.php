<?php

namespace Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\SmoothWeightedRoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class SmoothWeightedRoundRobinStrategyTest extends TestCase
{
    /**
     * @return SmoothWeightedRoundRobinStrategy
     */
    public function testSmoothWeightedRoundRobin(): SmoothWeightedRoundRobinStrategy
    {
        $nodes = [
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ];

        $collection = new Collection($nodes);

        $strategy = new SmoothWeightedRoundRobinStrategy();

        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(1, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));

        return $strategy;
    }

    /**
     * @param SmoothWeightedRoundRobinStrategy $strategy
     *
     * @return string
     *
     * @depends testSmoothWeightedRoundRobin
     */
    public function testSerialize(SmoothWeightedRoundRobinStrategy $strategy): string
    {
        $serialized = serialize($strategy);

        $this->assertIsString($serialized);

        return $serialized;
    }

    /**
     * @param string $serialized
     *
     * @depends testSerialize
     */
    public function testUnserialize(string $serialized): void
    {
        $nodes = [
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ];

        $collection = new Collection($nodes);

        /** @var SmoothWeightedRoundRobinStrategy $strategy */
        $strategy = unserialize($serialized);

        $this->assertInstanceOf(SmoothWeightedRoundRobinStrategy::class, $strategy);

        $this->assertSame(2, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
        $this->assertSame(0, $strategy->getIndex($collection));
    }
}
