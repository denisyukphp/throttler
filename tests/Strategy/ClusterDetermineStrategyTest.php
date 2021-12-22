<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Strategy;

use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Strategy\ClusterDetermineStrategy;
use Orangesoft\Throttler\Strategy\ClusterSet;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Strategy\RandomStrategy;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use PHPUnit\Framework\TestCase;

class ClusterDetermineStrategyTest extends TestCase
{
    public function testClusterDetermine(): void
    {
        $collection = new Collection([
            new Node('node1'),
            new Node('node2'),
            new Node('node3'),
        ]);

        $strategy = new ClusterDetermineStrategy(...[
            new ClusterSet(new RoundRobinStrategy(new InMemoryCounter(start: 0)), ['cluster1', 'cluster2']),
            new ClusterSet(new RandomStrategy(), ['cluster3'])
        ]);

        $this->assertSame(0, $strategy->getIndex($collection, ['cluster_name' => 'cluster1']));
        $this->assertSame(1, $strategy->getIndex($collection, ['cluster_name' => 'cluster1']));
        $this->assertSame(2, $strategy->getIndex($collection, ['cluster_name' => 'cluster1']));
    }
}
