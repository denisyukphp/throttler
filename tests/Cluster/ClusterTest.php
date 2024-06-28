<?php

declare(strict_types=1);

namespace Orangesoft\Throttler\Tests\Cluster;

use Orangesoft\Throttler\Cluster\Cluster;
use Orangesoft\Throttler\Cluster\ClusterPool;
use Orangesoft\Throttler\Cluster\ClusterSet;
use Orangesoft\Throttler\Collection\InMemoryCollection;
use Orangesoft\Throttler\Collection\Node;
use Orangesoft\Throttler\Collection\NodeInterface;
use Orangesoft\Throttler\Counter\InMemoryCounter;
use Orangesoft\Throttler\RandomThrottler;
use Orangesoft\Throttler\RoundRobinThrottler;
use PHPUnit\Framework\TestCase;

final class ClusterTest extends TestCase
{
    public function testBalance(): void
    {
        $pool = new ClusterPool(
            new ClusterSet(new RoundRobinThrottler(new InMemoryCounter()), ['a']),
            new ClusterSet(new RandomThrottler(), ['b', 'c']),
        );
        $collection = new InMemoryCollection([
            new Node('192.168.0.1'),
            new Node('192.168.0.2'),
            new Node('192.168.0.3'),
        ]);
        $cluster = new Cluster('a', $collection);

        $expectedNodes = [
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
            '192.168.0.1',
            '192.168.0.2',
            '192.168.0.3',
        ];
        $actualNodes = [];

        for ($i = 0; $i < 6; ++$i) {
            $actualNodes[] = $cluster->balance($pool);
        }

        $this->assertSame($expectedNodes, array_map(static fn (NodeInterface $actualNode): string => $actualNode->getName(), $actualNodes));
    }
}
