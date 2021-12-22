<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\SmoothWeightedRoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class SmoothWeightedRoundRobinStrategyTest extends TestCase
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

    public function testSmoothWeightedRoundRobin(): string
    {
        $strategy = new SmoothWeightedRoundRobinStrategy();

        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(1, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));

        return serialize($strategy);
    }

    /**
     * @depends testSmoothWeightedRoundRobin
     */
    public function testRestartSmoothWeightedRoundRobin(string $serializedStrategy): void
    {
        /** @var SmoothWeightedRoundRobinStrategy $strategy */
        $strategy = unserialize($serializedStrategy);

        $this->assertEquals(2, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
        $this->assertEquals(0, $strategy->getIndex($this->collection));
    }
}
