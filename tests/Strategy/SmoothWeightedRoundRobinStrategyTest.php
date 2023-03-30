<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\SmoothWeightedRoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class SmoothWeightedRoundRobinStrategyTest extends TestCase
{
    /**
     * @var array<int, Node>
     */
    private array $expectedNodes;

    protected function setUp(): void
    {
        $this->expectedNodes = [
            new Node('node1', 5),
            new Node('node2', 1),
            new Node('node3', 1),
        ];
    }

    public function testSmoothWeightedRoundRobin(): string
    {
        $strategy = new SmoothWeightedRoundRobinStrategy();
        $collection = new Collection($this->expectedNodes);

        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[1], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));

        return serialize($strategy);
    }

    /**
     * @depends testSmoothWeightedRoundRobin
     */
    public function testRestartSmoothWeightedRoundRobin(string $serializedStrategy): void
    {
        /** @var SmoothWeightedRoundRobinStrategy $strategy */
        $strategy = unserialize($serializedStrategy);
        $collection = new Collection($this->expectedNodes);

        $this->assertSame($this->expectedNodes[2], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
    }
}
