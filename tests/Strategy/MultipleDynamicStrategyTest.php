<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\MultipleDynamicStrategy;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use PHPUnit\Framework\TestCase;

class MultipleDynamicStrategyTest extends TestCase
{
    public function testMultipleDynamic(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $strategy = new MultipleDynamicStrategy(...[
            new RoundRobinStrategy(new InMemoryCounter(start: 0)),
            new RandomStrategy(),
        ]);

        $this->assertSame(0, $strategy->getIndex($collection, ['strategy_name' => RoundRobinStrategy::class]));
        $this->assertSame(1, $strategy->getIndex($collection, ['strategy_name' => RoundRobinStrategy::class]));
        $this->assertSame(2, $strategy->getIndex($collection, ['strategy_name' => RoundRobinStrategy::class]));
    }
}
