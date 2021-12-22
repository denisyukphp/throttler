<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Collection;

use Orangesoft\Throttler\Collection\Cluster;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\Collection;
use Orangesoft\Throttler\Strategy\InMemoryCounter;
use Orangesoft\Throttler\Strategy\RoundRobinStrategy;
use Orangesoft\Throttler\Throttler;
use PHPUnit\Framework\TestCase;

class ClusterTest extends TestCase
{
    public function testBalance(): void
    {
        $throttler = new Throttler(
            new RoundRobinStrategy(
                new InMemoryCounter()
            )
        );

        $expectedNode = new Node('node1');

        $collection = (new Collection())->addNode($expectedNode);

        $cluster = new Cluster('cluster1', $collection);

        $this->assertSame($expectedNode, $cluster->balance($throttler));
    }
}
