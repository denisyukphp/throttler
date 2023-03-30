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

    public function testWeightedRoundRobin(): InMemoryCounter
    {
        $counter = new InMemoryCounter(start: 0);
        $strategy = new WeightedRoundRobinStrategy($counter);
        $collection = new Collection($this->expectedNodes);

        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));

        return $counter;
    }

    /**
     * @depends testWeightedRoundRobin
     */
    public function testRestartWeightedRoundRobin(InMemoryCounter $counter): void
    {
        $strategy = new WeightedRoundRobinStrategy($counter);
        $collection = new Collection($this->expectedNodes);

        $this->assertSame($this->expectedNodes[1], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[2], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
    }
}
