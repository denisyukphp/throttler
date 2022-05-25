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
    /**
     * @var array<int, Node>
     */
    private array $expectedNodes;

    public function setUp(): void
    {
        $this->expectedNodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];
    }

    /**
     * @return InMemoryCounter
     */
    public function testRoundRobin(): InMemoryCounter
    {
        $counter = new InMemoryCounter(start: 0);
        $strategy = new RoundRobinStrategy($counter);
        $collection = new Collection($this->expectedNodes);

        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[1], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[2], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));

        return $counter;
    }

    /**
     * @depends testRoundRobin
     */
    public function testRoundRobinRestart(InMemoryCounter $counter): void
    {
        $strategy = new RoundRobinStrategy($counter);
        $collection = new Collection($this->expectedNodes);

        $this->assertSame($this->expectedNodes[1], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[2], $strategy->getNode($collection));
        $this->assertSame($this->expectedNodes[0], $strategy->getNode($collection));
    }
}
