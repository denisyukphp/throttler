<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\MultipleDynamicStrategy;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class MultipleDynamicStrategyTest extends TestCase
{
    public function testMultipleDynamic(): void
    {
        $expectedNodes = [
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ];

        $collection = new Collection($expectedNodes);

        $strategy = new MultipleDynamicStrategy(...[
            new RoundRobinStrategy(new InMemoryCounter(start: 0)),
            new RandomStrategy(),
        ]);

        $this->assertSame($expectedNodes[0], $strategy->getNode($collection, ['strategy_name' => RoundRobinStrategy::class]));
        $this->assertSame($expectedNodes[1], $strategy->getNode($collection, ['strategy_name' => RoundRobinStrategy::class]));
        $this->assertSame($expectedNodes[2], $strategy->getNode($collection, ['strategy_name' => RoundRobinStrategy::class]));
    }
}
